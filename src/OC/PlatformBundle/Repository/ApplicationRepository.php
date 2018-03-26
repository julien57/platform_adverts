<?php

namespace OC\PlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ApplicationRepository extends EntityRepository
{
    /**
     * Récupère les dernieres candidatures avec une $limit et l'annonce associée
     *
     * @param $limit
     */
    public function getApplicationsWithAdvert($limit)
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->innerJoin('a.advert', 'ad')
            ->addSelect('ad');

        $qb->setMaxResults($limit);

        $qb->getQuery()->getResult();
    }
}
