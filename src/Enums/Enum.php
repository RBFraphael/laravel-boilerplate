<?php

namespace RBFraphael\LaravelBoilerplate\Enums;

class Enum
{
    public static function values(): array
    {
        $reflection = new \ReflectionClass(static::class);
        $constants = $reflection->getConstants();
        return array_values($constants);
    }

    public static function label($value): string
    {
        return $value;
    }
}
