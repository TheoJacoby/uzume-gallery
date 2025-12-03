<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Painting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Création de commentaires pour chaque peinture
        for ($i = 0; $i < 20; $i++) {
            $painting = $this->getReference('painting_' . $i, Painting::class);
            
            // Création de 2-5 commentaires par peinture
            $commentCount = $this->faker->numberBetween(2, 5);
            $paintingCreated = \DateTime::createFromImmutable($painting->getCreated());
            
            for ($j = 0; $j < $commentCount; $j++) {
                $comment = new Comment();
                $comment->setContent($this->faker->paragraph(2));
                $comment->setRating($this->faker->numberBetween(1, 5));
                
                // Convertir DateTime en DateTimeImmutable
                $commentDate = $this->faker->dateTimeBetween($paintingCreated, 'now');
                $comment->setCreatedAt(\DateTimeImmutable::createFromMutable($commentDate));
                $comment->setIsVisible($this->faker->boolean(90)); // 90% de chance d'être visible
                $comment->setPainting($painting);
                
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PaintingFixtures::class,
        ];
    }
}



