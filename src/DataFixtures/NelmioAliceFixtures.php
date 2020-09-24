<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\Service\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class NelmioAliceFixtures extends Fixture
{
    private $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $em)
    {
        $loader = new NativeLoader();
        
        $entities = $loader->loadFile(__DIR__.'/fixtures.yaml')->getObjects();
        
        foreach ($entities as $entity) {
           
            if ($entity instanceof Movie) {
                $entity->setSlug($this->slugger->slugify($entity->getTitle()));
            }

            $em->persist($entity);
        };
        
        $em->flush();
    }
}
