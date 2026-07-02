<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Planning;

class PlanningFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $dateSemaineDerniere = new \DateTime(('monday last week'));
        $dateSemaineEnCours = new \DateTime(('monday this week'));
        $dateSemaineProchaine = new \DateTime(('monday next week'));

//========================
// Chef d'équipe
// =======================
        $chef = $this->getReference('user-chef-1', \App\Entity\User::class);

        $planning1 = new Planning();
        $planning1->setDateDebutSemaine($dateSemaineDerniere)
                    ->setUser($chef);
        $manager->persist($planning1);
        $this->addReference('planning-chef-1-passe', $planning1);

        $planning2 = new Planning();
        $planning2->setDateDebutSemaine($dateSemaineEnCours)
                    ->setUser($chef);
        $manager->persist($planning2);
        $this->addReference('planning-chef-1-courante', $planning2);

        $planning3 = new Planning();
        $planning3->setDateDebutSemaine($dateSemaineProchaine)
                    ->setUser($chef);
        $manager->persist($planning3);
        $this->addReference('planning-chef-1-futur', $planning3);

//========================
// Users
// =======================
        for($i=1; $i<=14; $i++ )
            {
                $employe = $this->getReference('user-employe-' .$i, \App\Entity\User::class);

                // Semaine passée
                $plPasse = new Planning();
                $plPasse->setDateDebutSemaine($dateSemaineDerniere)->setUser($employe);
                $manager->persist($plPasse);
                $this->addReference('planning-employe-' .$i. '-passe', $plPasse);

                // Semaine courante
                $plCourant = new Planning();
                $plCourant->setDateDebutSemaine($dateSemaineEnCours)->setUser($employe);
                $manager->persist($plCourant);
                $this->addReference('planning-employe-' .$i. '-courante', $plCourant);

                // Semaine futur
                $plFutur = new Planning();
                $plFutur->setDateDebutSemaine($dateSemaineProchaine)->setUser($employe);
                $manager->persist($plFutur);
                $this->addReference('planning-employe-' .$i. '-futur', $plFutur);
            }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
