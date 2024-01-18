<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Level;

class LevelFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $levels = ["Gris", "Violet", "Jaune", "Bleu", "Orange", "Vert", "Blanc", "Physique"];

        foreach ($levels as $lname) {
            $level = new Level();
            $level->setLabel($lname);
            $manager->persist($level);
        }

        $manager->flush();
    }
}
