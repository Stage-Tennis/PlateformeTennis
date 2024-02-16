<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Validator\ValidRole;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Validator\EmailNotAlreadyUsed;

#[Route("/api/users")]
class ApiNewUserController extends AbstractController
{
    #[Route('/new', name: 'app_api_user_new', methods: ["POST"])]
    public function new_user(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserRepository $userRepository, EntityManagerInterface $objectManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        try {
            $requestBody = $serializer->deserialize($request->getContent(), NewUserRequestBody::class, "json");

            $violations = $validator->validate($requestBody);
            // If mail is not valid
            if (count($violations) !== 0) {
                return new Response($serializer->serialize($violations, "json"), Response::HTTP_BAD_REQUEST, [
                    'Content-Type' => 'application/json'
                ]);
            }

            // If mail already in database
            if ($userRepository->alreadyExists($requestBody->email)) {
                $responseBody = [
                    "error" => "Ce mail est déjà utilisé.",
                ];

                return new Response($serializer->serialize($responseBody, "json"), Response::HTTP_BAD_REQUEST, [
                    'Content-Type' => 'application/json'
                ]);
            }

            $user = new User();
            $user->setName($requestBody->name);
            $user->setSurname($requestBody->surname);
            $user->setEmail($requestBody->email);
            $user->setRoles($requestBody->roles);
            $user->setFirstConnection(false);

            $password = $this->generateFirstPassword();
            $user->setPassword($passwordHasher->hashPassword($user, $password));

            $objectManager->persist($user);
            $objectManager->flush();

            $responseBody = [
                "user" => $user,
                "password" => $password
            ];

            return new Response($serializer->serialize($responseBody, "json", [
                "groups" => ["on_creation_infos"]
            ]), Response::HTTP_OK, [
                'Content-Type' => 'application/json'
            ]);
        } catch (NotEncodableValueException $e) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function generateFirstPassword(): string
    {
        $passwordLength = 12;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $special = '!@#$%^&*()_+{}|:<>?';
        $buf = "";

        for ($i = 0; $i < $passwordLength - 3; $i++) {
            $buf .= $characters[rand(0, strlen($characters) - 1)];
        }
        for ($i = 0; $i < 3; $i++) {
            $buf .= $special[rand(0, strlen($special) - 1)];
        }

        return $buf;
    }
}

class NewUserRequestBody
{
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    public string $name;

    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    public string $surname;

    #[Assert\NotBlank(message: "L'adresse e-mail est obligatoire.")]
    #[Assert\Email(message: "L'adresse e-mail est invalide.")]
    #[EmailNotAlreadyUsed()]
    public string $email;

    #[Assert\All([new ValidRole()])]
    public array $roles;
}
