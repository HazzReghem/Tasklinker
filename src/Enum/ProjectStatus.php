<?php

namespace App\Enum;

enum ProjectStatus: string
{
    case Archived = 'archived';
    case Active = 'active';
    
    public function getLabel(): string
    {
        return match ($this) {
            self::Archived => 'Archivé',
            self::Active => 'Actif',
        };
    }
}