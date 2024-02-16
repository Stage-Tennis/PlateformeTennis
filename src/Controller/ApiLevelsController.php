<?php

namespace App\Controller;

use App\Repository\LevelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Roles;

#[Route("/api/levels")]
class ApiLevelsController extends AbstractController
{
    #[Route('/all', name: 'app_api_levels_all', methods: ["GET"])]
    public function all_levels(LevelRepository $levelRepository, SerializerInterface $serializer): Response
    {
        $levels = $levelRepository->findAll();
        $response = $serializer->serialize($levels, "json", [
            "groups" => ["basic_infos"],
        ]);

        return new Response($response, Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
    }

    #[Route('/{id}', name: 'app_api_levels_id', methods: ["GET"])]
    public function level_by_id(int $id, LevelRepository $levelRepository, SerializerInterface $serializer): Response
    {
        $level = $levelRepository->findOneBy(["id" => $id]);
        $response = $serializer->serialize($level, "json", [
            "groups" => ["basic_infos"],
        ]);

        return new Response($response, Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
    }

    #[IsGranted(Roles::ROLE_ADMIN->value)]
    #[Route('/edit/{id}', name: 'app_api_levels_edit', methods: ["PATCH"])]
    public function level_edit(int $id, Request $request, EntityManagerInterface $manager, LevelRepository $levelRepository, SerializerInterface $serializer): Response
    {
        try {
            $body = $serializer->deserialize($request->getContent(), LevelEditRequestBody::class, "json");
            $level = $levelRepository->findOneBy(["id" => $id]);
            if ($body->new_label != null)
                $level->setLabel($body->new_label);

            if ($body->new_color != null)
                $level->setColor($body->new_color);

            $manager->persist($level);
            $manager->flush();

            return new Response('{}', Response::HTTP_OK, [
                "Content-Type" => "application/json"
            ]);
        } catch (NotEncodableValueException $e) {
            $body = [
                "error" => $e->getMessage(),
            ];
            return new Response($serializer->serialize($body, "json"), Response::HTTP_BAD_REQUEST, [
                "Content-Type" => "application/json"
            ]);
        } catch (\Exception $e) {
            $body = [
                "error" => $e->getMessage(),
            ];
            return new Response($serializer->serialize($body, "json"), Response::HTTP_INTERNAL_SERVER_ERROR, [
                "Content-Type" => "application/json"
            ]);
        }
    }
}

class LevelEditRequestBody
{
    public ?string $new_label;
    public ?string $new_color;

    public function __construct(?string $new_label = null, ?string $new_color = null)
    {
        $this->new_label = $new_label;
        $this->new_color = $new_color;
    }
}
