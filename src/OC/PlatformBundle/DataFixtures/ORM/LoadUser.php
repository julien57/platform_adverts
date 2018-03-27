<?php

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $listNames = ['Alexandre', 'Martin', 'Anna'];

        foreach ($listNames as $name) {
            $user = new User();

            $user->setUsername($name);
            $user->setPassword($name);
            $user->setSalt('');
            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
