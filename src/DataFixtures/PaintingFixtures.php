<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Painting;
use App\Entity\Technique;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaintingFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;
    private string $projectDir;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->faker = Factory::create('fr_FR');
        $this->projectDir = $parameterBag->get('kernel.project_dir');
    }

    public function load(ObjectManager $manager): void
    {
        // Récupérer les objets Category
        $categories = $manager->getRepository(Category::class)->findAll();
        // Récupérer les objets User
        $users = $manager->getRepository(User::class)->findAll();
        // Récupérer les objets Technique
        $techniques = $manager->getRepository(Technique::class)->findAll();

        // Création des peintures
        for ($i = 1; $i <= 20; $i++) {
            $painting = new Painting();
            $painting->setTitle($this->faker->sentence(4))
                ->setDescription($this->faker->paragraph(3))
                ->setCreated(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-2 years', 'now')))
                ->setHeight($this->faker->randomFloat(2, 30, 200))
                ->setWidth($this->faker->randomFloat(2, 30, 200))
                ->setIsPublished($this->faker->boolean(80))
                ->setCategory($categories[array_rand($categories)])
                ->setUser($users[array_rand($users)]);
            
            // Association à 1-3 techniques aléatoires
            $techniqueCount = $this->faker->numberBetween(1, 3);
            $selectedTechniques = $this->faker->randomElements($techniques, $techniqueCount);
            foreach ($selectedTechniques as $technique) {
                $painting->addTechnique($technique);
            }
            
            // Assigner une image : utilise les 12 images de manière cyclique (1-12, puis recommence)
            // Pour avoir des images variées, on utilise modulo 12 + 1 pour avoir 1-12
            $imageNumber = (($i - 1) % 12) + 1;
            $painting->setImageName($imageNumber . '.jpeg');
            $painting->setUpdatedAt(new \DateTimeImmutable());
            
            $manager->persist($painting);
            $this->addReference('painting_' . ($i - 1), $painting);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
            TechniqueFixtures::class,
        ];
    }
}

