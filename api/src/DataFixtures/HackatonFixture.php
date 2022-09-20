<?php

namespace App\DataFixtures;

use App\Entity\Hackaton;
use Bezhanov\Faker\Provider\Commerce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class HackatonFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Commerce($faker));

        //Create 20 hackatons
        for ($i=0; $i<20; $i++){
            $hackaton = new Hackaton();
            $hackaton->setName($faker->productName);
            $hackaton->setCustomer($faker->name);
            $hackaton->setStartDate($faker->dateTimeBetween('-1 years', 'now'));
            $hackaton->setEndDate($faker->dateTimeBetween('now', '+1 years'));
            $manager->persist($hackaton);
        }

        $manager->flush();
    }
}
