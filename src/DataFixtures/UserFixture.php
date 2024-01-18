<?php

namespace App\DataFixtures;

use App\Entity\Civility;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Level;
use App\Entity\Roles;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\User\Role;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class UserFixture extends Fixture implements DependentFixtureInterface
{
    private ObjectManager $manager;
    private PasswordHasherInterface $passwordHasher;

    public function __construct(PasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function create_user(Roles $role)
    {
        $manager = $this->manager;
        $faker = \Faker\Factory::create('fr_FR');
        $civilities = $manager->getRepository(Civility::class)->findAll();
        $levels = $manager->getRepository(Level::class)->findAll();

        $user = new User();
        $user->setEmail($faker->email);
        $user->setPassword($this->passwordHasher->hash("password"));
        $user->setName($faker->firstName);
        $user->setSurname($faker->lastName);
        $user->setPhone($faker->phoneNumber);
        $user->setAddress($faker->address);
        $user->setZipcode($faker->postcode);
        $user->setCity($faker->city);
        $user->addLevel($faker->randomElement($levels));
        $user->setCivility($faker->randomElement($civilities));
        $user->setRoles([$role]);

        $manager->persist($user);
    }
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $user = $this->create_user(Roles::ROLE_ADMIN);
        $user = $this->create_user(Roles::ROLE_SUPERVISOR);
        $user = $this->create_user(Roles::ROLE_USER);
        $user = $this->create_user(Roles::ROLE_USER);
        $user = $this->create_user(Roles::ROLE_TRAINER);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            LevelFixture::class,
            CivilityFixture::class,
        ];
    }
}
