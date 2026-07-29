<?php

namespace App\Controller;

use App\Repository\ArriveeDuJourRepository;
use App\Repository\NavireRepository;
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

    #[Route('/previsions', name: 'app_previsions_du_jour')]
    public function previsions(): Response
    {
        return $this->render('mouvement/prevision.html.twig', [
            'controller_name' => 'MouvementController',
        ]);
    }
}
