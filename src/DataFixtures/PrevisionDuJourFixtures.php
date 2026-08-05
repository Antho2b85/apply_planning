<?php

namespace App\DataFixtures;

use App\Entity\PrevisionDuJour;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PrevisionDuJourFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($position = 1; $position <= 3; $position++) {
            $prevision = new PrevisionDuJour();
            $prevision->setPosition($position);
            $prevision->setCreatedAt(new \DateTimeImmutable());
            $prevision->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($prevision);
        }
        $manager->flush();
    }
}
