<?php

namespace App\Factory;

use App\Entity\Employee;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use App\Enum\EmployeeStatus;

/**
 * @extends PersistentProxyObjectFactory<Employee>
 */
final class EmployeeFactory extends PersistentProxyObjectFactory
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
        return Employee::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $firstName = self::faker()->unique()->firstName();
        $lastName = self::faker()->unique()->lastName();
        $email = strtolower($firstName . '.' . $lastName . '@driblet.com');

        return [
            'firstName' => $firstName,
            'hireDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'lastName' => $lastName,
            'status' => self::faker()->randomElement(EmployeeStatus::cases()),
            'email' => $email,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Employee $employee): void {})
        ;
    }
}
