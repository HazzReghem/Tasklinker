<?php

namespace App\Enum;

enum EmployeeStatus: string
{
    case CDI = 'CDI';
    case CDD = 'CDD';
    case Freelance = 'Freelance';
    
    public function getLabel(): string
    {
        return match ($this) {
            self::CDI => 'CDI',
            self::CDD => 'CDD',
            self::Freelance => 'Freelance',
        };
    }
}