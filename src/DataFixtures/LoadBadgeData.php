<?php

namespace App\DataFixtures;

use App\Entity\Badge;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadBadgeData extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $badge = new Badge();
        $badge->setName("Timide");
        $badge->setActionCount(1);
        $badge->setActionName("comment");

        $manager->persist($badge);

        $badge = new Badge();
        $badge->setName("Pipelette");
        $badge->setActionCount(2);
        $badge->setActionName("comment");

        $manager->persist($badge);
        $manager->flush();
    }
}