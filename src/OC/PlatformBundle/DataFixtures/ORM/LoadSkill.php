<?php

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Skill;

class LoadSkill implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $names = [
            'PHP',
            'Symfony',
            'C++',
            'Java',
            'Photoshop',
            'Blender',
            'Javascript',
        ];

        foreach ($names as $name) {
            $skill = new Skill();
            $skill->setName($name);

            $manager->persist($skill);
        }

        $manager->flush();
    }
}
