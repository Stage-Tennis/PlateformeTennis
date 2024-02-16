<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Roles as Roles;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ApiRolesController extends AbstractController
{
    #[Route('/api/roles/all', name: 'app_api_roles', methods: ["GET"])]
    public function index(SerializerInterface $serializer): Response
    {
        $roles = Roles::cases();

        $formattedRoles = [];

        foreach ($roles as $role) {
            $formattedRole = str_replace('ROLE_', '', $role->value);
            $formattedRole = ucfirst(strtolower($formattedRole));

            $formattedRoles[] = $formattedRole;
        }

        $response = [];
        for ($i = 0; $i < count($roles); $i++) {
            $response[] = new RoleApiObject($roles[$i]->value, $formattedRoles[$i]);
        }

        return new Response($serializer->serialize($response, "json"), Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
    }
}

class RoleApiObject
{
    public string $role;
    public string $display_name;
    public function __construct(string $role, string $display_name)
    {
        $this->role = $role;
        $this->display_name = $display_name;
    }
}
