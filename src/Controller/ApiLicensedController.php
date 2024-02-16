<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Roles;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Level;
use App\Entity\Civility;
use App\Validator\Constraints\UserEmailRequirements;
use App\Validator\EmailNotAlreadyUsed;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api/users")]
class ApiLicensedController extends AbstractController
{
    /**
     * GET all licensed users in JSON format.
     * It only serializes basic informations of the users, see in [the User entity](src/Entity/User.php).
     * Also see an example use in [the LicensedPanel component](../../assets/svelte/controllers/admin_panel/LicensedPanel.svelte).
     */
    #[Route('/all/{page}', name: 'api_user_all', methods: ["GET"])]
    #[IsGranted(Roles::ROLE_ADMIN->value)]
    public function get_all_users(UserRepository $userRepository, SerializerInterface $serializer, int $page): Response
    {
        $responseBody = $serializer->serialize(new AllLicensedResponse($userRepository, $page), "json", [
            "groups" => ["basic_infos"]
        ]);

        return new Response($responseBody, Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
    }

    #[Route('/edit', name: 'api_edit_user', methods: ["PATCH"])]
    #[IsGranted(new Expression("is_authenticated()"))]
    public function edit_user(#[CurrentUser] User $user, Request $request, UserRepository $userRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        try {
            $requestBody = $serializer->deserialize($request->getContent(), EditLicensedRequestBody::class, "json");

            // If the user tries to edit another user and isn't admin
            if ($user->getId() != $requestBody->id && !$user->isAdmin())
                return new Response("You can only edit self", Response::HTTP_UNAUTHORIZED);

            // If the user tries to edit its own permissions, token amount or levels and isn't admin
            if (($requestBody->new_token_amount || $requestBody->new_roles || $requestBody->new_levels) && !$user->isAdmin())
                return new Response("Cannot edit one of these fields", Response::HTTP_UNAUTHORIZED);


            $validate = $validator->validate($requestBody);
            if (count($validate) !== 0)
                return new JsonResponse($serializer->serialize($validate, "json"), Response::HTTP_BAD_REQUEST);

            $target = $userRepository->find($requestBody->id);
            $result = $requestBody->execute($target, $entityManager);
            $entityManager->persist($result);
            $entityManager->flush();

            return new Response("{}", Response::HTTP_OK, [
                'Content-Type' => 'application/json'
            ]);
        } catch (NotEncodableValueException $e) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/delete/{id}', name: 'api_delete_user', methods: ["DELETE"])]
    #[IsGranted(new Expression("is_authenticated() and is_granted('ROLE_ADMINISTRATEUR')"))]
    public function delete_user(#[CurrentUser] User $user, int $id, EntityManagerInterface $objectManager): Response
    {
        $userRepository = $objectManager->getRepository(User::class);

        $target = $userRepository->find($id);

        if ($target == null)
            return new Response("", Response::HTTP_NOT_FOUND);

        if ($user->getId() == $target->getId())
            return new Response("You can't delete yourself", Response::HTTP_FORBIDDEN);


        $objectManager->remove($target);
        $objectManager->flush();

        return new Response("", Response::HTTP_OK);
    }

    #[Route('/delete_group', name: 'api_delete_user_group', methods: ["DELETE"])]
    #[IsGranted(new Expression("is_authenticated() and is_granted('ROLE_ADMINISTRATEUR')"))]
    public function delete_group(#[CurrentUser] User $currentUser, Request $request, EntityManagerInterface $objectManager, SerializerInterface $serializer): Response
    {
        $userRepository = $objectManager->getRepository(User::class);
        $body = $serializer->deserialize($request->getContent(), DeleteGroupRequestBody::class, "json");

        foreach ($body->group as $id) {
            $user = $userRepository->find($id);

            if ($user->getId() == $currentUser->getId()) {
                $response = ["message" => "Vous ne pouvez pas vous supprimer vous-même."];
                return new JsonResponse($response, Response::HTTP_FORBIDDEN);
            }

            if (!$user)
                return new Response("", Response::HTTP_NOT_FOUND);

            $objectManager->remove($user);
        }

        $objectManager->flush();
        return new Response("", Response::HTTP_OK);
    }

    #[Route('/find', name: 'api_find_user', methods: ["POST"])]
    #[IsGranted(new Expression("is_authenticated() and is_granted('ROLE_ADMINISTRATEUR')"))]
    public function user_find(Request $request, UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        try {
            $body = $serializer->deserialize($request->getContent(), FindUserRequestBody::class, "json");
            $firstPart = explode(" ", $body->name);
            $splitted = $firstPart !== false && strpos($body->name, " ") !== false;

            $returnTab = match ($splitted) {
                false => $userRepository->findAllLike($body->name, $body->name),

                true => $userRepository->findAllLike($firstPart[0], $firstPart[1]),
            };

            $responseBody = $serializer->serialize(new FindUserResponseBody($returnTab), "json", [
                "groups" => ["basic_infos"]
            ]);

            return new Response($responseBody, Response::HTTP_OK, [
                'Content-Type' => 'application/json'
            ]);
        } catch (NotEncodableValueException $e) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new Response($e->getMessage() . ": " . $e->getLine(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

class FindUserRequestBody
{
    public string $name;
}

class FindUserResponseBody
{
    #[Groups("basic_infos")]
    public int $count;
    #[Groups("basic_infos")]
    public array $all_licensed;

    #[Groups("basic_infos")]
    public int $page_count;

    public function __construct(array $users)
    {
        $this->count = count($users);
        $this->page_count = 1;
        $this->all_licensed = $users;
    }
}


class DeleteGroupRequestBody
{
    public array $group;
}

class AllLicensedResponse
{
    #[Groups("basic_infos")]
    public int $count;

    #[Groups("basic_infos")]
    public int $page_count;

    #[Groups("basic_infos")]
    public array $all_licensed;

    public function __construct(UserRepository $repo, int $page)
    {
        $pageCount = $repo->count([]) / 30;
        $pageCountModulo = $repo->count([]) % 30;

        if ($pageCountModulo > 0) $pageCount++;

        $this->page_count = $pageCount;
        $this->all_licensed = $repo->findPage($page, 30);
        $this->count = $repo->count([]);
    }
}

class EditLicensedRequestBody
{
    public int $id;
    public ?string $new_phone = null;
    public ?int $new_token_amount = null;

    #[Assert\AtLeastOneOf([
        new UserEmailRequirements(),
        new Assert\Blank()
    ])]
    public ?string $new_mail = null;
    public ?array $new_roles = null;
    public ?array $new_levels = null;

    public ?string $new_name = null;
    public ?string $new_surname = null;
    public ?string $new_birthdate = null;
    public ?string $new_address = null;
    public ?string $new_zipcode = null;
    public ?string $new_city = null;
    public ?int $new_sport_age = null;
    public ?string $new_civility = null;

    public function execute(User $user, EntityManagerInterface $entityManager): User
    {
        $civilityRepo = $entityManager->getRepository(Civility::class);
        $levelRepo = $entityManager->getRepository(Level::class);

        if ($this->new_phone) $user->setPhone($this->new_phone);
        if ($this->new_token_amount) $user->setTokenAmount($this->new_token_amount);
        if ($this->new_mail) $user->setEmail($this->new_mail);
        if ($this->new_roles) $user->setRoles($this->new_roles);
        if ($this->new_levels) {
            foreach ($this->new_levels as $level) {
                $level = $levelRepo->find($level);
                $user->addLevel($level);
            }
        }
        if ($this->new_name) $user->setName($this->new_name);
        if ($this->new_surname) $user->setSurname($this->new_surname);
        if ($this->new_birthdate) $user->setBirthdate(new \DateTime($this->new_birthdate));
        if ($this->new_address) $user->setAddress($this->new_address);
        if ($this->new_zipcode) $user->setZipcode($this->new_zipcode);
        if ($this->new_city) $user->setCity($this->new_city);
        if ($this->new_sport_age) $user->setSportAge($this->new_sport_age);
        if ($this->new_civility) {
            $new_civility = $civilityRepo->find($this->new_civility);
            $user->setCivility($new_civility);
        }
        return $user;
    }
}
