<?php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Event\MessagePostEvent;
use OC\PlatformBundle\Event\PlatformEvents;
use OC\PlatformBundle\Form\AdvertEditType;
use OC\PlatformBundle\Form\AdvertType;
use OC\PlatformBundle\Form\ClientType;
use OC\PlatformBundle\Form\ContactType;
use OC\PlatformBundle\Model\ClientDTO;
use OC\PlatformBundle\Model\ContactDTO;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdvertController extends Controller
{
    /**
     * @var Session
     */
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @param $page
     *
     * @return Response
     */
    public function indexAction(int $page): Response
    {
        if ($page < 1) {
            throw new NotFoundHttpException('La page '.$page.' est inexistante.');
        }

        $nbPerPage = 3;

        $listAdverts = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Advert::class)
            ->getAdverts($page, $nbPerPage);

        $nbPages = ceil(count($listAdverts) / $nbPerPage);

        if ($page > $nbPages) {
            throw $this->createNotFoundException('La page '.$page." n'existe pas.");
        }

        return $this->render('OCPlatformBundle:Advert:index.html.twig', [
            'listAdverts' => $listAdverts,
            'nbPages' => $nbPages,
            'page' => $page,
        ]);
    }

    /**
     * @param $id
     *
     * @return Response
     */
    public function viewAction(Advert $advert): Response
    {
        $em = $this->getDoctrine()->getManager();



        $listApplications = $em
            ->getRepository(Application::class)
            ->findBy(['advert' => $advert]);

        $listAdvertSkills = $em
            ->getRepository(AdvertSkill::class)
            ->findBy(['advert' => $advert]);

        return $this->render('OCPlatformBundle:Advert:view.html.twig', [
            'advert' => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvertSkills,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function addAction(Request $request)
    {
        $advert = new Advert();

        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            $this->session->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            return $this->redirectToRoute('oc_platform_view', ['id' => $advert->getId()]);
        }

        return $this->render('OCPlatformBundle:Advert:add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param $id
     * @param Request $request
     * @param Session $session
     *
     * @return RedirectResponse|Response
     */
    public function editAction(int $id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository(Advert::class)->find($id);

        if (is_null($advert)) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $form = $this->createForm(AdvertEditType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->session->getFlashBag()->add('notice', 'Annonce bien modifiée');

            return $this->redirectToRoute('oc_platform_view', ['id' => $advert->getId()]);
        }

        return $this->render('OCPlatformBundle:Advert:edit.html.twig', [
            'form' => $form->createView(),
            'advert' => $advert,
        ]);
    }

    /**
     * @param $id
     *
     * @return Response
     */
    public function deleteAction(Request $request, int $id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository(Advert::class)->find($id);

        if (is_null($advert)) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($advert);
            $em->flush();

            $this->session->getFlashBag()->add('notice', 'Annonce bien supprimée');

            return $this->redirectToRoute('oc_platform_home');
        }

        return $this->render('OCPlatformBundle:Advert:delete.html.twig', [
            'advert' => $advert,
            'form' => $form->createView(),
        ]);
    }

    public function menuAction(int $limit): Response
    {
        $em = $this->getDoctrine()->getManager();

        $listAdverts = $em->getRepository(Advert::class)->findBy(
            [],
            ['date' => 'DESC'],
            $limit,
            0
        );

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', ['listAdverts' => $listAdverts]);
    }

    /**
     * @param int $days
     *
     * @return RedirectResponse
     */
    public function purgeAction(int $days): RedirectResponse
    {
        $purgerAdvert = $this->get('oc_platform.purger.advert');

        $countAdverts = $purgerAdvert->purge($days);

        $this->session->getFlashBag()->add('purgeInfo', 'Les annonces ont bien été purgées : '.$countAdverts.' annonces.');

        return $this->redirectToRoute('oc_platform_home');
    }

    public function contactAction(Request $request)
    {
        $contactDTO = new ContactDTO();

        $form = $this->createForm(ContactType::class, $contactDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
        }

        return $this->render('@OCPlatform/Advert/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function clientAction(Request $request)
    {
        $clientDTO = new ClientDTO();

        $form = $this->createForm(ClientType::class, $clientDTO);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            dump($form->getData());
        }

        return $this->render('@OCPlatform/Advert/client.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function testAction()
    {
        $advert = new Advert();
        $advert->setDate(new \DateTime());
        $advert->setContent('Abcd');

        $listErrors = $this->get('validator')->validate($advert);

        if (count($listErrors) > 0) {
            return new Response((string) $listErrors);
        } else {
            return new Response("L'annonce est valide !");
        }
    }

    public function translationAction($name)
    {
        return $this->render('OCPlatformBundle:Advert:translation.html.twig', ['name' => $name]);
    }

    /**
     * @ParamConverter("json")
     */
    public function ParamConverterAction($json)
    {
        return new Response(print_r($json, true));
    }
}
