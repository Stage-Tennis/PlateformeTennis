<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CourseType;

class CourseTypeFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $courseTypes = ["Mini-Tennis", "Enfant", "Adulte", "Préparation Physique"];

        foreach ($courseTypes as $ctname) {
            $courseType = new CourseType();
            $courseType->setLabel($ctname);
            $manager->persist($courseType);
        }

        $manager->flush();
    }
}
