<?php

namespace App\DataFixtures;

use App\Entity\DocumentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $types = [
            'tech',
            'marketing',
            'designer',
            'general',
            'autre'
        ];

        foreach ($types as $typeName) {
            $type = new DocumentType();
            $type->setName($typeName);
            $manager->persist($type);
        }
        $manager->flush();
    }
}
