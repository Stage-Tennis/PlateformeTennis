<?php

namespace App\Controller;

use App\Entity\Roles;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManageLicensedController extends AbstractController
{
    #[Route('/licensed', name: 'app_manage_licensed')]
    public function index(UserRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $has_perm = array(Roles::ROLE_ADMIN, $user->getRoles());

        $has_perm = true;
        if ($has_perm) {
            return $this->render('manage_licensed/index.html.twig', [
                'controller_name' => 'ManageLicensedController',
                "all_licensed" => $repo->findAll(),
            ]);
        }

        return new RedirectResponse("/");
    }
}
