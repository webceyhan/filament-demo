<?php

namespace App\Traits;

trait HasEnumAccessors
{
    public static function keys(): array
    {
        return array_column(static::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }

    public static function options(): array
    {
        return array_combine(static::values(), static::keys());
    }
}
