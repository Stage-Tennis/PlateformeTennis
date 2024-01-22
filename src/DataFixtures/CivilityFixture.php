<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Civility;

class CivilityFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $civility = new Civility();
        $civility->setLabel('Monsieur');
        $manager->persist($civility);

        $civility = new Civility();
        $civility->setLabel('Madame');
        $manager->persist($civility);

        $manager->flush();
    }
}
