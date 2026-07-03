<?php

namespace App\DataFixtures;

use App\Entity\Navire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NavireFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $navirePossible = ['Capu Rossu', 'A Galeotta', 'Pascal Paoli', 'Vizzavona', 'Capu di Muru', 'Paglia Orba', 'Jean Nicoli', 'Danielle Casanova', 'Méditerrannée'];

        foreach ($navirePossible as $key => $nomBateau) {

            $navire = new Navire();
            $navire->setNom($nomBateau);
            $manager->persist($navire);
            $this->addReference('navire_' . $key, $navire);
        }

        $manager->flush();
    }
}
