<?php

namespace App\DataFixtures;

use App\Entity\Creneau;
use App\Entity\Planning;
use App\Entity\UserCreneau;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CreneauFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Les postes de travail
    $postesPossibles = ['RESA/TAXE/CONV', 'CALL ENTRANT', 'GUICHET/GM', 'DEB AC', 'RESERVES AC', 'RESERVES', 'PK', 'PT DEB', 'PT EMB', 'GUICHET'];

    // Boucle des employés
        for($i=1; $i<=14; $i++)
            {
                $employe = $this->getReference('user-employe-' .$i, \App\Entity\User::class);
                $planning = $this->getReference('planning-employe-' .$i. '-courante', Planning::class);

                // Génération sur les jours de la semaine
        for($jour=0; $jour<=7; $jour++)
            {
                $dateCreneau = clone $planning->getDateDebutSemaine();
                $dateCreneau = $dateCreneau->modify("$jour day");

                // Heure de début arrondie
                $heureDebutInt = rand(5, 19);
                $heureDebutStr = sprintf('%02d:00:00', $heureDebutInt);

                // Heure de fin arrondie
                $heureFinInt = rand($heureDebutInt +2, 24);

                // Je sécurise pour rester sur la même journée dans la BDD
                if($heureFinInt === 24)
                    {
                        $heureFinStr = "23:59:59";
                    } else {
                        $heureFinStr = sprintf('%02d:00:00', $heureFinInt);
                    }

                    // Durée de travail en heures pour la journée
                    $duree = $heureFinInt - $heureDebutInt;

                    // Poste au hasard
                    $posteChoisi = $postesPossibles[array_rand($postesPossibles)];


                    // Création du créneau
                    $creneau = new Creneau();
                    $creneau->setDate($dateCreneau)
                            ->setHeureDebut(new \DateTime(($heureDebutStr)))
                            ->setHeureFin(new \DateTime(($heureFinStr)))
                            ->setDuree($duree)
                            ->setPoste($posteChoisi)
                            ->setPlanningId($planning);

                            $creneau->setCreatedAt(new \DateTimeImmutable());
                            $creneau->setUpdatedAt(new \DateTimeImmutable());

                            $manager->persist($creneau);

        
        $link = new UserCreneau();
        $link->setUser($employe);
        $link->setCreneau($creneau);
        $link->setCreatedAt(new \DateTimeImmutable());
        $link->setUpdatedAt(new \DateTimeImmutable());
        
        $manager->persist($link);
            }
            }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PlanningFixtures::class,
        ];
    }
}
