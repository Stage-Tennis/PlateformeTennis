<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanelController extends AbstractController
{
    #[Route('/panel', name: 'app_panel')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $response = match ($user->getRoles()[0]) {
            'ROLE_ADMIN' => $this->render('panel/admin.html.twig'),
            'ROLE_TRAINER' => $this->render('panel/trainer.html.twig'),
            'ROLE_USER' => $this->render('panel/user.html.twig'),
            default => new Response("Unknown role", 500),
        };

        return $response;
    }
}
