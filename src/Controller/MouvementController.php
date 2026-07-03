<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MouvementController extends AbstractController
{
    #[Route('/arrivee', name: 'app_arrivees_du_jour')]
    public function arrivee(): Response
    {
        return $this->render('mouvement/arrivee.html.twig', [
            'controller_name' => 'MouvementController',
        ]);
    }

    #[Route('/previsions', name: 'app_previsions_du_jour')]
    public function previsions(): Response
    {
        return $this->render('mouvement/prevision.html.twig', [
            'controller_name' => 'MouvementController',
        ]);
    }
}
