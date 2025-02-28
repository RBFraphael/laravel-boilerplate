<?php

namespace RBFraphael\LaravelBoilerplate\Traits;

trait StringEnum
{
    public static function label(self $case): string
    {
        if ($case->value) {
            return $case->value;
        }
        return $case->name;
    }

    public static function values(): array
    {
        return array_map(function (self $case) {
            return $case->name;
        }, self::cases());
    }

    public static function fromCase(string $searchCase): self|null
    {
        foreach (self::cases() as $case) {
            if ($case->name == $searchCase) {
                return $case;
            }
        }

        return null;
    }
}
