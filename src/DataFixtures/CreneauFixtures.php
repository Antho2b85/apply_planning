<?php

namespace App\DataFixtures;

use App\Entity\Creneau;
use App\Entity\Planning;
use App\Entity\UserCreneau;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\NavireFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CreneauFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Les postes de travail
        $postesPossibles = ['RESA/TAXE/CONV', 'CALL ENTRANT', 'GUICHET/GM', 'DEB AC', 'RESERVES AC', 'RESERVES', 'PK', 'PT DEB', 'PT EMB', 'GUICHET'];

        // Boucle des employés
        for ($i = 1; $i <= 14; $i++) {
            $employe = $this->getReference('user-employe-' .$i, \App\Entity\User::class);
            $planning = $this->getReference('planning-employe-' .$i. '-courante', Planning::class);

            // Génération sur les jours de la semaine
            for ($jour = 0; $jour <= 6; $jour++) {
                $dateCreneau = (clone $planning->getDateDebutSemaine())->modify("+{$jour} days");

                $keys = array_rand($postesPossibles, 2);
                $posteMatin = $postesPossibles[$keys[0]];
                $posteAprem = $postesPossibles[$keys[1]];



                // MATIN 5h–12h
                $debutMatin = (clone $dateCreneau)->setTime(5, 0);
                $finMatin   = (clone $dateCreneau)->setTime(12, 0);
                $dureeMatin = (int)(($finMatin->getTimestamp() - $debutMatin->getTimestamp()) / 60);

                $creneauMatin = new Creneau();
                $creneauMatin->setDate($dateCreneau)
                    ->setHeureDebut($debutMatin)
                    ->setHeureFin($finMatin)
                    ->setPoste($posteMatin)
                    ->setDuree($dureeMatin)
                    ->setTypeShift('MATIN')
                    ->setNavire($this->getReference('navire_' . rand(0, 8), \App\Entity\Navire::class))
                    ->setPlanningId($planning)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTimeImmutable());

                $manager->persist($creneauMatin);

                $linkMatin = new UserCreneau();
                $linkMatin->setUser($employe)
                    ->setCreneau($creneauMatin)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTimeImmutable());

                $manager->persist($linkMatin);
                // =================================================================

                // APRES-MIDI 14h–19h
                $debutAprem = (clone $dateCreneau)->setTime(14, 0);
                $finAprem   = (clone $dateCreneau)->setTime(19, 0);
                $dureeAprem = (int)(($finAprem->getTimestamp() - $debutAprem->getTimestamp()) / 60);

                $creneauAprem = new Creneau();
                $creneauAprem->setDate($dateCreneau)
                    ->setHeureDebut($debutAprem)
                    ->setHeureFin($finAprem)
                    ->setPoste($posteAprem)
                    ->setDuree($dureeAprem)
                    ->setTypeShift('APRES_MIDI')
                    ->setNavire($this->getReference('navire_' . rand(0, 8), \App\Entity\Navire::class))
                    ->setPlanningId($planning)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTimeImmutable());

                $manager->persist($creneauAprem);

                $linkAprem = new UserCreneau();
                $linkAprem->setUser($employe)
                    ->setCreneau($creneauAprem)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTimeImmutable());

                $manager->persist($linkAprem);
            }
        }

        // Planning chef d'equipe
        $chef = $this->getReference('user-chef-1', \App\Entity\User::class);
        $planningChef = $this->getReference('planning-chef-1-courante', Planning::class);

        for ($jour = 0; $jour <= 6; $jour++) {
            $dateCreneau = (clone $planningChef->getDateDebutSemaine())->modify("+{$jour} days");

            $keys = array_rand($postesPossibles, 2);
            $posteMatin = $postesPossibles[$keys[0]];
            $posteAprem = $postesPossibles[$keys[1]];

            // MATIN
            $debutMatin = (clone $dateCreneau)->setTime(5, 0);
            $finMatin   = (clone $dateCreneau)->setTime(12, 0);
            $dureeMatin = (int)(($finMatin->getTimestamp() - $debutMatin->getTimestamp()) / 60);

            $creneauMatin = new Creneau();
            $creneauMatin->setDate($dateCreneau)
                ->setHeureDebut($debutMatin)
                ->setHeureFin($finMatin)
                ->setPoste($posteMatin)
                ->setDuree($dureeMatin)
                ->setTypeShift('MATIN')
                ->setNavire($this->getReference('navire_' . rand(0, 8), \App\Entity\Navire::class))
                ->setPlanningId($planningChef)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($creneauMatin);

            $linkMatin = new UserCreneau();
            $linkMatin->setUser($chef)
                ->setCreneau($creneauMatin)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($linkMatin);

            // APRES-MIDI
            $debutAprem = (clone $dateCreneau)->setTime(14, 0);
            $finAprem   = (clone $dateCreneau)->setTime(19, 0);
            $dureeAprem = (int)(($finAprem->getTimestamp() - $debutAprem->getTimestamp()) / 60);

            $creneauAprem = new Creneau();
            $creneauAprem->setDate($dateCreneau)
                ->setHeureDebut($debutAprem)
                ->setHeureFin($finAprem)
                ->setPoste($posteAprem)
                ->setDuree($dureeAprem)
                ->setTypeShift('APRES_MIDI')
                ->setNavire($this->getReference('navire_' . rand(0, 8), \App\Entity\Navire::class))
                ->setPlanningId($planningChef)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($creneauAprem);

            $linkAprem = new UserCreneau();
            $linkAprem->setUser($chef)
                ->setCreneau($creneauAprem)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($linkAprem);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PlanningFixtures::class,
            NavireFixtures::class,
        ];
    }
}
