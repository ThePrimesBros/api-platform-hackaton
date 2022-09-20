<?php

namespace App\DataFixtures;

use App\Entity\Document;
use App\Entity\DocumentType;
use App\Entity\Hackaton;
use App\Repository\DocumentTypeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class DocumentFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $documentRepository = $manager->getRepository(DocumentType::class);
        $hackatonRepository = $manager->getRepository(Hackaton::class);

        $hackatons = $hackatonRepository->findAll();
        $types = $documentRepository->findAll();

        for ($i=0; $i < 50; $i++) {
            //find a random hackaton
            $hackaton = $hackatons[array_rand($hackatons)];
            $document = new Document();
            $document->setName('Document ' . $i);
            $document->setFile('file' . $i);
            $document->setType($types[rand(0, count($types) - 1)]);
            $document->setHackaton($hackaton);
            $manager->persist($document);
            $hackaton->addDocument($document);
            $manager->persist($hackaton);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TypeFixture::class,
            HackatonFixture::class,
        ];
    }
}
