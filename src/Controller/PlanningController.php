<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlanningController extends AbstractController
{
    #[Route('/', name: 'app_planning')]
    public function index(): Response
    {
        return $this->render('planning/individuel.html.twig', [
            'controller_name' => 'PlanningController',
        ]);
    }

    #[Route('/planning-general', name: 'app_planning_general')]
    public function planningGeneral(): Response
    {
        return $this->render('planning/general.html.twig', [
            'controller_name' => 'PlanningController',
        ]);
    }


    #[Route('/admin/planning', name: 'app_admin_planning')]
    public function adminPlanning(): Response
    {
        return $this->render('planning/general.html.twig', [
            'controller_name' => 'PlanningController',
        ]);
    }
}
