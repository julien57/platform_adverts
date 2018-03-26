<?php

namespace OC\PlatformBundle\Purger;

use Doctrine\ORM\EntityManager;
use OC\PlatformBundle\Email\ApplicationMailer;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\AdvertSkill;

class AdvertPurger
{
    /**
     * @var ApplicationMailer
     */
    private $applicationMailer;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(ApplicationMailer $applicationMailer, EntityManager $em)
    {
        $this->applicationMailer = $applicationMailer;
        $this->em = $em;
    }

    /**
     * @param int $days
     *
     * @return int
     */
    public function purge(int $days)
    {
        $advertRepository = $this->em->getRepository(Advert::class);
        $listAdverts = $advertRepository->purgeOutdatedAdverts($days);

        $advertSkillRepository = $this->em->getRepository(AdvertSkill::class);
        $advertSkills = $advertSkillRepository->findByAdvert($listAdverts);

        foreach ($advertSkills as $advertSkill) {
            $this->em->remove($advertSkill);
        }

        foreach ($listAdverts as $advert) {
            $this->applicationMailer->purgeAdvertNotification($advert);
            $this->em->remove($advert);
        }

        $this->em->flush();

        return count($listAdverts);
    }
}
