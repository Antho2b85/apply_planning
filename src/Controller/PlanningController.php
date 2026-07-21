<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Absence;
use App\Entity\Creneau;
use App\Entity\UserCreneau;
use App\Repository\CreneauRepository;
use App\Repository\UserCreneauRepository;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use IntlDateFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PlanningController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/', name: 'app_planning')]

    // Planning individuel de l'agent connecté
    public function index(UserCreneauRepository $userCreneauRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $userCreneaus = $userCreneauRepository->findBy(['user' => $user]);
        return $this->render('planning/individuel.html.twig', [
            'userCreneaus' => $userCreneaus,
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/planning-general', name: 'app_planning_general')]
    // Vue générale du planning accessible à tous
    public function planningGeneral(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('planning/general.html.twig', [
            'users' => $users,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/planning/form', name: 'app_admin_planning_form', methods:['POST'])]
    public function aceptRequestPost(
        Request $request,
        CreneauRepository $creneauRepository,
        \Doctrine\ORM\EntityManagerInterface $em,
        UserRepository $userRepository,
        \App\Repository\PlanningRepository $planningRepository
    ): Response {
        // Vérification du token CSRF
        if ($this->isCsrfTokenValid('modifier_planning', $request->request->get('_token'))) {
            // Récupération des données du formulaire
            $horairesMatinDebut = $request->request->get('horairesMatinDebut');
            $horairesMatinFin = $request->request->get('horairesMatinFin');
            $horairesApremDebut = $request->request->get('horairesApremDebut');
            $horairesApremFin = $request->request->get('horairesApremFin');
            $creneauMatinId = $request->request->get('creneauMatinId');
            $creneauApremId = $request->request->get('creneauApremId');
            $userId = $request->request->get('userId');
            $date = $request->request->get('date');

            // Récupération des entités User et Planning
            $dateObj = new DateTime($date);
            $user = $userRepository->find($userId);
            $planning = $planningRepository->findByUserAndWeek($user, $dateObj);
            if ($user !== null && $planning !== null) {
                // Création ou modification du créneau matin
                if (empty($creneauMatinId)) {
                    // Nouveau créneau matin
                    $creneauMatin = new Creneau();

                    if (!empty($horairesMatinDebut) && !empty($horairesMatinFin)) {
                        $creneauMatin->setHeureDebut(DateTime::createFromFormat('H:i', $horairesMatinDebut));
                        $creneauMatin->setHeureFin(DateTime::createFromFormat('H:i', $horairesMatinFin));
                    }

                    $creneauMatin->setPoste($request->request->get('post-matin'));
                    $creneauMatin->setPlanningId($planning);
                    $creneauMatin->setTypeShift('MATIN');
                    $creneauMatin->setCreatedAt(new DateTimeImmutable());
                    $creneauMatin->setUpdatedAt(new DateTimeImmutable());

                    $debut = DateTime::createFromFormat('H:i', $horairesMatinDebut);
                    $fin = DateTime::createFromFormat('H:i', $horairesMatinFin);
                    $duree = ($fin->getTimestamp() - $debut->getTimestamp()) / 60;

                    $creneauMatin->setDuree((int)$duree);
                    $creneauMatin->setDate($dateObj);
                    $em->persist($creneauMatin);

                    $userCreneau = new UserCreneau();
                    $userCreneau ->setUser($user);
                    $userCreneau ->setCreneau($creneauMatin);
                    $userCreneau->setCreatedAt(new DateTimeImmutable());
                    $userCreneau->setUpdatedAt(new DateTimeImmutable());
                    $em->persist($userCreneau);

                } else {
                    // Modification du créneau matin existant
                    $creneauMatin = $creneauRepository->find($creneauMatinId);
                    if (!empty($horairesMatinDebut) && !empty($horairesMatinFin)) {
                        $creneauMatin->setHeureDebut(DateTime::createFromFormat('H:i', $horairesMatinDebut));
                        $creneauMatin->setHeureFin(DateTime::createFromFormat('H:i', $horairesMatinFin));
                    }
                    $creneauMatin->setPoste($request->request->get('post-matin'));
                }

                // Création ou modification du créneau apres midi
                if (empty($creneauApremId)) {
                    // Nouveau créneau apres midi
                    $creneauAprem = new Creneau();
                    if (!empty($horairesMatinDebut) && !empty($horairesMatinFin)) {
                        $creneauAprem->setHeureDebut(DateTime::createFromFormat('H:i', $horairesApremDebut));
                        $creneauAprem->setHeureFin(DateTime::createFromFormat('H:i', $horairesApremFin));
                    }
                    $creneauAprem->setPoste($request->request->get('post-aprem'));
                    $creneauAprem->setPlanningId($planning);
                    $creneauAprem->setTypeShift('APRES_MIDI');
                    $creneauAprem->setCreatedAt(new DateTimeImmutable());
                    $creneauAprem->setUpdatedAt(new DateTimeImmutable());

                    $debut = DateTime::createFromFormat('H:i', $horairesApremDebut);
                    $fin = DateTime::createFromFormat('H:i', $horairesApremFin);
                    $duree = ($fin->getTimestamp() - $debut->getTimestamp()) / 60;

                    $creneauAprem->setDuree((int)$duree);
                    $creneauAprem->setDate($dateObj);
                    $em->persist($creneauAprem);

                    $userCreneau = new UserCreneau();
                    $userCreneau ->setUser($user);
                    $userCreneau ->setCreneau($creneauAprem);
                    $userCreneau->setCreatedAt(new DateTimeImmutable());
                    $userCreneau->setUpdatedAt(new DateTimeImmutable());
                    $em->persist($userCreneau);

                } else {
                    // Modification créneau apres midi existant
                    $creneauAprem = $creneauRepository->find($creneauApremId);
                    if (!empty($horairesMatinDebut) && !empty($horairesMatinFin)) {
                        $creneauAprem->setHeureDebut(DateTime::createFromFormat('H:i', $horairesApremDebut));
                        $creneauAprem->setHeureFin(DateTime::createFromFormat('H:i', $horairesApremFin));
                    }
                    $creneauAprem->setPoste($request->request->get('post-aprem'));
                }
            }

            // Sauvegarde en BDD
            $em->flush();

            return $this->json(['status' => 'ok']);
        } else {
            return $this->json(['status' => 'error', 'message' => 'Token CSRF invalide']);
        }
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/admin/planning/{offset}', name: 'app_admin_planning', defaults: ['offset' => 0])]
    public function adminPlanning(
        int $offset,
        UserRepository $userRepository,
        CreneauRepository $creneauRepository,
        \App\Repository\AbsenceRepository $absenceRepository,
    ): Response {

        // 1. Récupération des agents
        $usersTeam1 = $userRepository->findAgentsByTeam('1');
        $usersTeam2 = $userRepository->findAgentsByTeam('2');

        // Calcul du lundi de la semaine courante + décalage offset
        $dateTime = new DateTime();
        $jourSemaine = (int)$dateTime->format('N');
        $debutSemaine = (clone $dateTime)->modify('-' .($jourSemaine - 1) . ' days');
        $debutSemaine ->setTime(0, 0, 0);
        $debutSemaine->modify(($offset * 7) . ' days');

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

        $allUsers = array_merge($usersTeam1, $usersTeam2);
        foreach ($allUsers as $user) {

            if (!$user instanceof User || $user->getId() === null) {
                continue;
            }

            $userId = $user->getId()->toRfc4122(); //  conversion UUID → string

            foreach ($semaineDates as $date) {

                $totalMinutes = $creneauRepository->getTotalMinutesForUserOnDate($user, $date);

                $totaux[$userId][$date->format('Y-m-d')] = [
                    'minutes'  => $totalMinutes
                ];
            }
        }

        // Récupération des absences par agent et par jour
        $absences = [];
        foreach ($allUsers as $user) {
            $userId = $user->getId()->toRfc4122();

            foreach ($semaineDates as $date) {
                $absenceUser = $absenceRepository->findAbsenceForUserOnDate($user, $date);
                $absences [$userId] [$date->format('Y-m-d')] = [
                    'absenceUser' => $absenceUser
                ];
            }
        }


        // 5. Retour normal
        return $this->render('planning/general.html.twig', [
            'usersTeam1'    => $usersTeam1,
            'usersTeam2'    => $usersTeam2,
            'numeroSemaine' => $numeroSemaine,
            'texteDebut'    => $texteDebut,
            'texteFin'      => $texteFin,
            'semaineDates'  => $semaineDates,
            'totaux'        => $totaux,
            'offset'        => $offset,
            'absences'      => $absences
        ]);
    }

    // Sauvegarde d'une absence en BDD
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/absence/form', name: 'app_admin_abscence_form', methods:['POST'])]

    public function saveAbsence(
        Request $request,
        \Doctrine\ORM\EntityManagerInterface $em,
        UserRepository $userRepository
    ): Response {
        if ($this->isCsrfTokenValid('absence', $request->request->get('_token'))) {

            $motif = $request->request->get('motif');
            $absenceUserId = $request->request->get('absenceUserId');
            $debutAbs = $request->request->get('debutAbs');
            $finAbs = $request->request->get('finAbs');

            $user = $userRepository->find($absenceUserId);

            if (empty($motif) || empty($absenceUserId) || empty($debutAbs)) {
                return $this->json(['status' => 'error', 'message' => 'Données manquantes']);
            }
            $absence = new Absence();
            $absence->setMotif($motif);
            $absence->setDateDebut(new DateTime($debutAbs));
            if (empty($finAbs)) {
                $absence->setDateFin(null);
            } else {
                $absence->setDateFin(new DateTime($finAbs));
            }
            $absence->setUser($user);

            $em->persist($absence);
            $em->flush();
            return $this->json(['status' => 'ok']);
        } else {
            return $this->json(['status' => 'error', 'message' => 'Token CSRF invalide']);
        }
    }

    // Création d'un nouvelle agent via le modal admin
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/agent/nouveau', name: 'app_admin_add', methods:['POST'])]
    public function addAgent(
        Request $request,
        \Doctrine\ORM\EntityManagerInterface $em,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        if ($this->isCsrfTokenValid('ajouter_agent', $request->request->get('_token'))) {
            $email = $request->request->get('email');
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $equipe = $request->request->get('equipe-select');
            $role = $request->request->get('role-select');
            $motDePasseTemp = bin2hex(random_bytes(8));


            if (empty($email) || empty($nom) || empty($prenom) || empty($equipe) || empty($role)) {
                return $this->json(['status' => 'error', 'message' => 'Données manquantes']);
            }
            if (!str_ends_with($email, '@corsicalinea.com')) {
                return $this->json(['status' => 'error', 'message' => 'Email invalide']);
            }

            $agent = new User();
            $agent ->setEmail($email);
            $agent ->setNom($nom);
            $agent ->setPrenom($prenom);
            $agent ->setEquipe($equipe);
            $agent ->setRoles([$role]);
            $agent->setFirstLogin(true);
            $hashedPassword = $userPasswordHasher->hashPassword($agent, $motDePasseTemp);
            $agent->setPassword($hashedPassword);

            $em->persist($agent);
            $em->flush();
            return $this->json(['status' => 'ok']);
        } else {
            return $this->json(['status' => 'error']);
        }
    }
}
