<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\RequestMatcher\IsJsonRequestMatcher;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Roles;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

use function PHPSTORM_META\type;

class ApiLicensedController extends AbstractController
{
    /**
     * GET all licensed users in JSON format.
     * It only serializes basic informations of the users, see in [the User entity](src/Entity/User.php).
     * Also see an example use in [the LicensedPanel component](../../assets/svelte/controllers/admin_panel/LicensedPanel.svelte).
     */
    #[Route('/api/licensed/all', name: 'api_licensed_all', methods: ["GET"])]
    #[IsGranted(Roles::ROLE_ADMIN->value)]
    public function get_all_licensed(UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        $users = $userRepository->findAll();

        $responseBody = $serializer->serialize($users, "json", [
            "groups" => ["basic_infos"]
        ]);

        return new Response($responseBody, Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
    }

    #[Route('/api/licensed/edit', name: 'api_edit_licensed', methods: ["GET"])]
    #[IsGranted(new Expression("is_authenticated()"))]
    public function edit_licensed(#[CurrentUser] ?User $user, Request $request, UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        $jsonMatcher = new IsJsonRequestMatcher();

        try {
            $requestBody = $serializer->deserialize($request->getContent(), EditLicensedRequestBody::class, "json");

            // If request is invalid
            if (!$jsonMatcher->matches($request) || ($requestBody instanceof EditLicensedRequestBody))
                return new Response("", Response::HTTP_BAD_REQUEST);

            // If the user tries to edit another user and isn't admin
            if ($user->getId() != $requestBody->target_id && !in_array(Roles::ROLE_ADMIN->value, $user->getRoles()))
                return new Response("", Response::HTTP_UNAUTHORIZED);
        } catch (NotEncodableValueException $_) {
            return new Response("", Response::HTTP_BAD_REQUEST);
        }
    }
}

class EditLicensedRequestBody
{
    public int $target_id;

    public ?string $new_mail;
    public ?array $new_roles;
    public ?string $new_name;
    public ?string $new_surname;
    public ?string $new_birthdate;
    public ?string $new_address;
    public ?string $new_zipcode;
    public ?string $new_city;
    public ?string $new_phone;
    public ?int $new_sport_age;
    public ?string $new_civility;
    public ?array $new_level;
}
