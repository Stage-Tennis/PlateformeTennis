<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Roles as Roles;
use Symfony\Component\HttpFoundation\RequestMatcher\IsJsonRequestMatcher;

class ApiRolesController extends AbstractController
{
    #[Route('/api/roles/all', name: 'app_api_roles', methods: ["GET"])]
    public function index(): JsonResponse
    {
        $roles = Roles::cases();

        $formattedRoles = [];

        foreach ($roles as $role) {
            $formattedRole = str_replace('ROLE_', '', $role->value);
            $formattedRole = ucfirst(strtolower($formattedRole));

            $formattedRoles[] = $formattedRole;
        }

        return new JsonResponse(new ResponseBody($roles, $formattedRoles), JsonResponse::HTTP_OK);
    }
}

class ResponseBody
{
    public array $roles;
    public array $display_names;

    public function __construct(array $roles, array $display_names)
    {
        $this->roles = $roles;
        $this->display_names = $display_names;
    }
}
