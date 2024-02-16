<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Roles;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[IsGranted(new Expression("is_authenticated()"))]
class PanelController extends AbstractController
{
    #[Route('/panel', name: 'app_panel')]
    public function index(#[CurrentUser] User $user): Response
    {
        $response = match ($user->getRoles()[0]) {
            Roles::ROLE_ADMIN->value => $this->render('panel/admin.html.twig'),
            Roles::ROLE_TRAINER->value => $this->render('panel/trainer.html.twig'),
            Roles::ROLE_USER->value => $this->render('panel/user.html.twig'),
            default => new Response("Unknown role: " . $user->getRoles()[0], 500),
        };

        if ($user->isFirstConnection()) $response = new RedirectResponse("/premiereConnexion");
        
        return $response;
    }
}
