<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Planning;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use function PHPUnit\Framework\assertTrue;

class PlanningFixture extends Fixture implements DependentFixtureInterface
{
    public function entry($start, $end, $level, $ctype)
    {
        $date = new \DateTimeImmutable();
        $start_time = new \DateTimeImmutable($start);
        $end_time = new \DateTimeImmutable($end);

        $planning = new Planning();
        $planning->setDate($date);
        $planning->setStartTime($start_time);
        $planning->setEndTime($end_time);
        $planning->setLevel($level);
        $planning->setCourseType($ctype);

        return $planning;
    }
    public function load(ObjectManager $manager): void
    {
        $levels = $manager->getRepository('App\Entity\Level');
        $course_types = $manager->getRepository('App\Entity\CourseType');

        assertTrue($levels->findOneBy(['label' => 'Orange']) != null);

        $manager->persist(
            $this->entry("10:00", "11:00", $levels->findOneBy(['label' => 'Gris']), $course_types->findOneBy(['label' => 'Mini-Tennis']))
        );
        $manager->persist($this->entry("11:00", "12:00", $levels->findOneBy(['label' => 'Orange']), $course_types->findOneBy(['label' => 'Enfant'])));
        $manager->persist($this->entry("13:00", "14:30", $levels->findOneBy(['label' => 'Vert']), $course_types->findOneBy(['label' => 'Adulte'])));
        $manager->persist($this->entry("14:30", "16:00", $levels->findOneBy(['label' => 'Physique']), $course_types->findOneBy(['label' => 'Préparation Physique'])));

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LevelFixture::class,
            CourseTypeFixture::class,
        ];
    }
}
