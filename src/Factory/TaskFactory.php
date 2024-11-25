<?php

namespace App\Factory;

use App\Entity\Task;
use App\Entity\Project;
use App\Entity\Employee;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Enum\TaskStatus;

/**
 * @extends PersistentProxyObjectFactory<Task>
 */
final class TaskFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public static function class(): string
    {
        return Task::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'deadline' => self::faker()->dateTimeBetween('now', '+1 year'),
            'description' => self::faker()->text(255),
            'status' => self::faker()->randomElement(TaskStatus::cases()),
            'title' => self::faker()->text(50),
            'project' => ProjectFactory::random(),
        ];
    }

    /**wxc >ee https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Task $task): void {})
        ;
    }
}
