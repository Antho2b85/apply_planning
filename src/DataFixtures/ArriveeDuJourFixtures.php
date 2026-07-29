<?php

namespace App\DataFixtures;

use App\Entity\ArriveeDuJour;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArriveeDuJourFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($position = 1; $position <= 3; $position++) {
            $arrivee = new ArriveeDuJour();
            $arrivee->setPosition($position);
            $arrivee->setCreatedAt(new \DateTimeImmutable());
            $arrivee->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($arrivee);

        }

        $manager->flush();
    }
}
