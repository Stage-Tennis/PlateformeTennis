<?php

namespace App\DataFixtures;

use App\Entity\Civility;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Level;
use App\Entity\Roles;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture implements DependentFixtureInterface
{
    private ObjectManager $manager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
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
        $user->setPassword($this->passwordHasher->hashPassword($user, "password"));
        $user->setName($faker->firstName);
        $user->setSurname($faker->lastName);
        $user->setPhone("0123456789");
        $user->setAddress($faker->address);
        $user->setZipcode($faker->postcode);
        $user->setCity($faker->city);
        $user->addLevel($faker->randomElement($levels));
        $user->setCivility($faker->randomElement($civilities));
        $user->setRoles([$role]);
        $user->setBirthdate($faker->dateTimeBetween('-50 years', '-18 years'));
        $user->setFirstConnection(true);
        $user->setSportAge($faker->numberBetween(1, 10));
        $user->setTokenAmount($faker->numberBetween(0, 100));
        $user->setLicenseSerial(strval($faker->numberBetween(1000000, 9999999)) . $faker->randomLetter());

        $manager->persist($user);
    }
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->create_user(Roles::ROLE_ADMIN);
        $this->create_user(Roles::ROLE_USER);
        $this->create_user(Roles::ROLE_USER);
        $this->create_user(Roles::ROLE_TRAINER);

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
