<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompetitionsController extends AbstractController
{
    #[Route('/competitions', name: 'app_competitions')]
    public function index(): Response
    {
        return $this->render('competitions/index.html.twig', [
            'controller_name' => 'CompetitionsController',
        ]);
    }
}
