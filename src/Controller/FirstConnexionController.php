<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Civility;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FirstConnexionController extends AbstractController
{
    #[IsGranted(new Expression("is_authenticated()"))]
    #[Route('/premiereConnexion', name: 'app_premiereConnexion', methods: ['GET', 'POST'])]
    public function index(#[CurrentUser] User $user, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($request->isMethod('POST')) {
            $password = $passwordHasher->hashPassword($user, $request->request->get('newPassword'));
            $licenseSerial = $request->request->get('license_serial');
            $name = $request->request->get('name');
            $surname = $request->request->get('surname');
            
            $civilityString = $request->request->get('civility');
            $civility = $entityManager->getRepository(Civility::class)->findOneBy(["id" => $civilityString]);
            if (!$civility) return new Response("Civilité invalide: " . $civilityString, Response::HTTP_BAD_REQUEST);

            $birthdate = $request->request->get('birthdate');
            $email = $request->request->get('email');
            $phone = $request->request->get('phone');
            $address = $request->request->get('address');
            $zipcode = $request->request->get('zipcode');
            $city = $request->request->get('city');
            $startTennis = $request->request->get('startTennis');

            $now = date("Y");
            $sportAge = $now - $startTennis;
            
        
            $user->setPassword($password);
            $user->setLicenseSerial($licenseSerial);
            $user->setName($name);
            $user->setSurname($surname);
            $user->setCivility($civility);
            $user->setBirthDate(\DateTime::createFromFormat('Y-m-d', $birthdate));
            $user->setEmail($email);   
            $user->setPhone($phone);
            $user->setAddress($address);
            $user->setZipcode($zipcode);
            $user->setCity($city);
            $user->setSportAge($sportAge);
            $user->setFirstConnection(false);

            $entityManager->persist($user);
            $entityManager->flush();


            return new RedirectResponse("/login");
        }
        else {
        return $this->render('first_connexion/index.html.twig', [
            'controller_name' => 'FirstConnexionController',
            "civilities" => $entityManager->getRepository(Civility::class)->findAll()
        ]);
        }
    }
}
