<?php

namespace App\Enum;

enum TaskStatus: string
{
    case ToDo = 'to do';
    case Doing = 'doing';
    case Done = 'done';
    
    public function getLabel(): string
    {
        return match ($this) {
            self::ToDo => 'To Do',
            self::Doing => 'Doing',
            self::Done => 'Done',
        };
    }
}