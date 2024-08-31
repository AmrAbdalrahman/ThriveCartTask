<?php

namespace App\Enums;

trait BaseEnum
{
    /**
     * Get all values of the enum.
     *
     * @return array
     */
    public static function all(): array
    {
        $values = [];
        foreach ((new \ReflectionClass(static::class))->getConstants() as $constant) {
            $values[] = $constant->value;
        }
        return $values;
    }
}
