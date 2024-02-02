<?php

namespace App\Controller;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdhererController extends AbstractController
{
    #[Route('/adherer', name: 'app_adherer')]
    public function index(): Response
    {
        $year = new DateTimeImmutable();
        $currentMonth = (int)date('m');
        $seasonStartYear = date('Y');

        if ($currentMonth >= 8) {
            $seasonStartYear++;
        }
        else {
            $seasonStartYear--;
        }
        
        $currentSeason = $seasonStartYear . '/' . ($seasonStartYear + 1);

        return $this->render('adherer/index.html.twig', [
            'N' => $year->format('Y'),
            'N-1' => $year->modify('-1 year'),
            'cas1' => $year->modify('-5 years'),
            'cas2' => $year->modify('-10 years'),
            'cas3' => $year->modify('-6 years'),
            'cas4' => $year->modify('-18 years'),
            'currentSeason' => $currentSeason,
            'controller_name' => 'AdhererController',
        ]);
    }
}
