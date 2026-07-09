<?php

namespace App\Controller;

use App\Repository\UserCreneauRepository;
use App\Repository\UserRepository;
use DateTime;
use IntlDateFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PlanningController extends AbstractController
{
    #[Route('/', name: 'app_planning')]
    public function index(UserCreneauRepository $userCreneauRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $userCreneaus = $userCreneauRepository->findBy(['user' => $user]);
        return $this->render('planning/individuel.html.twig', [
            'userCreneaus' => $userCreneaus,
        ]);
    }

    #[Route('/planning-general', name: 'app_planning_general')]
    public function planningGeneral(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('planning/general.html.twig', [
            'users' => $users,
        ]);
    }

    #[IsGranted(('ROLE_ADMIN'))]
    #[Route('/admin/planning/{team}', name: 'app_admin_planning')]
    public function adminPlanning(
        string $team,
        UserRepository $userRepository,
        \App\Repository\CreneauRepository $creneauRepository
    ): Response {

        // 1. Récupération des agents
        $users = $userRepository->findAgentsByTeam($team);

        // 2. Calcul des dates de la semaine
        $dateTime = new DateTime();
        $debutSemaine = (clone $dateTime)->modify('Monday this week');

        $semaineDates = [];
        for ($jour = 0; $jour <= 6; $jour++) {
            $semaineDates[] = (clone $debutSemaine)->modify("+$jour day");
        }

        $finSemaine = (clone $dateTime)->modify('Sunday this week');

        // 3. Formatage FR
        $fmtDebut = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        $fmtDebut->setPattern('d MMMM');

        $fmtFin = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);

        $texteDebut = $fmtDebut->format($debutSemaine);
        $texteFin = $fmtFin->format($finSemaine);
        $numeroSemaine = $dateTime->format('W');

        // 4. Calcul des totaux
        $totaux = [];

        foreach ($users as $user) {

            if (!$user instanceof \App\Entity\User || $user->getId() === null) {
                continue;
            }

            $userId = $user->getId()->toRfc4122(); //  conversion UUID → string

            foreach ($semaineDates as $date) {

                $totalMinutes = $creneauRepository->getTotalMinutesForUserOnDate($user, $date);

                $totaux[$userId][$date->format('Y-m-d')] = [
                    'heures'  => $totalMinutes
                ];
            }
        }


        // 5. Retour normal
        return $this->render('planning/general.html.twig', [
            'users'         => $users,
            'current_team'  => $team,
            'numeroSemaine' => $numeroSemaine,
            'texteDebut'    => $texteDebut,
            'texteFin'      => $texteFin,
            'semaineDates'  => $semaineDates,
            'totaux'        => $totaux
        ]);
    }


}
