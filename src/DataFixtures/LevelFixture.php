<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Level;

class LevelFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $levels = ["Gris" => "#444554", "Violet" => "#6941c6", "Jaune" => "#ffff00", "Bleu" => "#0000ff", "Orange" => "#ff8500", "Vert" => "#00ff00", "Blanc" => "#dddddd", "Physique" => "#aaccff"];

        foreach ($levels as $label => $color) {
            $level = new Level();
            $level->setLabel($label);
            $level->setColor($color);
            $manager->persist($level);
        }

        $manager->flush();
    }
}
