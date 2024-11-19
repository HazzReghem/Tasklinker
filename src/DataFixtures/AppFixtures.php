<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Factory\ProjectFactory;
use App\Factory\TaskFactory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        ProjectFactory::createMany(2); 
        TaskFactory::createMany(5); 


        $manager->flush();
    }
}
