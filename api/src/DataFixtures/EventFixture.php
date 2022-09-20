<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Hackaton;
use Bezhanov\Faker\Provider\Commerce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EventFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Commerce($faker));
        $hackatons = $manager->getRepository(Hackaton::class)->findAll();

        //create 50 events
        for ($i=0; $i<50; $i++){
            $hackaton = $hackatons[array_rand($hackatons)];
            $event = new Event();
            $eventDate = $faker->dateTimeBetween($hackaton->getStartDate(), $hackaton->getEndDate());
            $event->setName($faker->productName);
            $event->setStartDate($eventDate);
            $event->setDescription($faker->text);
            $event->setReward('Reward ' . $i);
            $event->setHackaton($hackaton);
            $manager->persist($event);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            HackatonFixture::class
        ];
    }
}
