<?php

namespace OC\PlatformBundle\Email;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Application;

class ApplicationMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $email;

    public function __construct(\Swift_Mailer $mailer, string $email)
    {
        $this->mailer = $mailer;
        $this->email = $email;
    }

    /**
     * @param Application $application
     */
    public function sendNewNotification(Application $application)
    {
        $this->prepareMessage(
            'Nouvelle candidature',
            'Vous avez reçu une nouvelle candidature.',
        $application->getAdvert()->getEmail()
        );
    }

    /**
     * @param Advert $advert
     */
    public function purgeAdvertNotification(Advert $advert)
    {
        $this->prepareMessage(
            'Annonce supprimée',
            'Votre annonce vient d\'être supprimée car non modifiée et sans candidatures.',
            $advert->getEmail()
        );
    }

    /**
     * @param string $subject
     * @param string $body
     * @param string $to
     */
    private function prepareMessage(string $subject, string $body, string $to)
    {
        $message = new \Swift_Message(
            $subject, $body);

        $message
            ->addTo($to)
            ->addFrom($this->email);

        $this->mailer->send($message);
    }
}
