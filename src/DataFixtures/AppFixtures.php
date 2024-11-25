<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Factory\ProjectFactory;
use App\Factory\TaskFactory;
use App\Factory\EmployeeFactory;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $projects = ProjectFactory::createMany(2);

        // TaskFactory::createMany(5);

        $employees = EmployeeFactory::new()->createMany(5);

        // Créer une tâche par employé
        foreach ($employees as $employee) {
            $task = TaskFactory::new()->create([
                    'employee' => $employee
            ]);
        }
        
        // Associer des employés à des projets
        // foreach ($projects as $project) {
        //     foreach (array_slice($employees, 0, 2) as $employee) {
        //         $project->addEmployee($employee); 
        //     }
        // }


        $manager->flush();
    }
}
