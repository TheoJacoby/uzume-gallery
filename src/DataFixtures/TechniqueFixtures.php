<?php

namespace App\DataFixtures;

use App\Entity\Technique;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TechniqueFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $techniqueNames = ['Huile', 'Fusain', 'Acrylique', 'Aquarelle', 'Pastel'];
        
        foreach ($techniqueNames as $index => $name) {
            $technique = new Technique();
            $technique->setName($name);
            $manager->persist($technique);
            $this->addReference('technique_' . $index, $technique);
        }

        $manager->flush();
    }
}



