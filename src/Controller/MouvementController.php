<?php

namespace App\Controller;

use App\Repository\ArriveeDuJourRepository;
use App\Repository\NavireRepository;
use App\Repository\PrevisionDuJourRepository;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class MouvementController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/arrivee', name: 'app_arrivees_du_jour')]
    public function arrivee(ArriveeDuJourRepository $arriveeDuJourRepository, NavireRepository $navireRepository): Response
    {

        $arrivees = $arriveeDuJourRepository->findBy([], ['position' => 'ASC']);
        $navires = $navireRepository->findAll();
        return $this->render('mouvement/arrivee.html.twig', [
            'arrivees' => $arrivees,
            'navires' => $navires,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/arrivee/form', name: 'app_admin_arrivee_form', methods:['POST'])]
    public function customArriveePost(
        Request $request,
        \Doctrine\ORM\EntityManagerInterface $em,
        ArriveeDuJourRepository $arriveeDuJourRepository,
        NavireRepository $navireRepository
    ): Response {

        if ($this->isCsrfTokenValid('arrivee_jour', $request->request->get('_token'))) {
            $id = $request->request->get('id');
            $navireId = $request->request->get('navire');
            $heureArrivee = $request->request->get('heureArrivee');
            $heureDepart = $request->request->get('heureDepart');
            $quai = $request->request->get('quai');

            $arriveeDuJour = $arriveeDuJourRepository->find($id);

            if ($arriveeDuJour === null) {
                return $this->json(['status' => 'error', 'message' => 'Emplacement introuvable']);
            }

            $navire = !empty($navireId) ? $navireRepository->find($navireId) : null;
            $arriveeDuJour->setNavire($navire);

            $arriveeDuJour->setHeureArrivee(!empty($heureArrivee) ? DateTime::createFromFormat('H:i', $heureArrivee) : null);
            $arriveeDuJour->setHeureDepart(!empty($heureDepart) ? DateTime::createFromFormat('H:i', $heureDepart) : null);
            $arriveeDuJour->setQuai($quai);
            $arriveeDuJour->setUpdatedAt(new DateTimeImmutable());

            $em->flush();
            return $this->json(['status' => 'ok']);
        } else {
            return $this->json(['status' => 'error', 'message' => 'Token CSRF invalide']);
        }
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/previsions', name: 'app_previsions_du_jour')]
    public function previsions(
        PrevisionDuJourRepository $previsionDuJourRepository,
        ArriveeDuJourRepository $arriveeDuJourRepository
    ): Response {

        $previsions = $previsionDuJourRepository->findBy([], ['position' => 'ASC']);
        $arrivees = $arriveeDuJourRepository->findBy([], ['position' => 'ASC']);

        return $this->render('mouvement/prevision.html.twig', [
            'previsions' => $previsions,
            'arrivees' => $arrivees
            ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/prevision/form', name: 'app_admin_prevision_form', methods:['POST'])]
    public function customPrevisions(
        Request $request,
        \Doctrine\ORM\EntityManagerInterface $em,
        PrevisionDuJourRepository $previsionDuJourRepository
    ): Response {
        if ($this->isCsrfTokenValid('prevision_jour', $request->request->get('_token'))) {

            $id = $request->request->get('id');
            $prevision = $previsionDuJourRepository->find($id);
            if ($prevision === null) {
                return $this->json(['status' => 'error', 'message' => 'Prévisions introuvable']);
            }

            $agentsReserves = $request->request->get('agentsReserves');
            $totalRemorques = $request->request->get('totalRemorques');
            $embarque = $request->request->get('embarque');
            $titres = $request->request->get('titres');
            $attentes = $request->request->get('attentes');
            $agentsControle = $request->request->get('agentsControle');
            $totalPassagers = $request->request->get('totalPassagers');
            $autosBasses = $request->request->get('autosBasses');
            $hauteurs = $request->request->get('hauteurs');
            $attelages = $request->request->get('attelages');
            $motos = $request->request->get('motos');
            $controles = $request->request->get('controles');
            $aVenir = $request->request->get('aVenir');

            $prevision->setAgentsReserves($agentsReserves ?: null);
            $prevision->setTotalRemorques(!empty($totalRemorques) ? (int) $totalRemorques : null);
            $prevision->setEmbarque(!empty($embarque) ? (int) $embarque : null);
            $prevision->setTitres(!empty($titres) ? (int) $titres : null);
            $prevision->setAttentes(!empty($attentes) ? (int) $attentes : null);
            $prevision->setAgentsControle($agentsControle ?: null);
            $prevision->setTotalPassagers(!empty($totalPassagers) ? (int) $totalPassagers : null);
            $prevision->setAutosBasses(!empty($autosBasses) ? (int) $autosBasses : null);
            $prevision->setHauteurs(!empty($hauteurs) ? (int) $hauteurs : null);
            $prevision->setAttelages(!empty($attelages) ? (int) $attelages : null);
            $prevision->setMotos(!empty($motos) ? (int) $motos : null);
            $prevision->setControles(!empty($controles) ? (int) $controles : null);
            $prevision->setAvenir(!empty($aVenir) ? (int) $aVenir : null);
            $prevision->setUpdatedAt(new DateTimeImmutable());

            $em->flush();
            return $this->json(['status' => 'ok']);
        } else {
            return $this->json(['status' => 'error', 'message' => 'Token CSRF invalide']);
        }
    }
}
