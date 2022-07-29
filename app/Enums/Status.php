<?php

namespace App\Enums;

enum Status: string
{
    case Todo = 'todo';
    case Doing = 'doing';
    case Done = 'done';

    public const DEFAULT = self::Todo;
}
