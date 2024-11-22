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
        $namesToEmails = [
            'Natalie' => 'natalie@driblet.com',
            'Demi' => 'demi@driblet.com',
            'Marie' => 'marie@driblet.com',
        ];

        $firstName = self::faker()->unique()->randomElement(array_keys($namesToEmails));
        
        // Associer le prénom sélectionné à l'email correspondant
        $email = $namesToEmails[$firstName];

        return [
            'firstName' => $firstName,
            'hireDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'lastName' => self::faker()->unique()->randomElement(['Dillon', 'Dupont', 'Baker']),
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
