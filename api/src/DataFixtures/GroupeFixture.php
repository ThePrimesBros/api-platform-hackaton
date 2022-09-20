<?php

namespace App\DataFixtures;

use App\Entity\Groupe;
use App\Entity\Hackaton;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GroupeFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $hackatons = $manager->getRepository(Hackaton::class)->findAll();
        for ($i=0; $i<100; $i++){
            $hackaton = $hackatons[array_rand($hackatons)];
            $groupe = new Groupe();
            $groupe->setHackaton($hackaton);
            $manager->persist($groupe);
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
