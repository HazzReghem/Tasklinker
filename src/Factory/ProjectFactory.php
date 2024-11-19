<?php

namespace App\Factory;

use App\Entity\Project;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use App\Enum\ProjectStatus;

/**
 * @extends PersistentProxyObjectFactory<Project>
 */
final class ProjectFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Project::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'status' => self::faker()->randomElement(ProjectStatus::cases()),
            'title' => self::faker()->unique()->randomElement(['TaskLinker', 'Site vitrine Les Soeurs Marchand']),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Project $project): void {})
        ;
    }
}
