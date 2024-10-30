<?php

namespace WMIP2C\Common\Services;

abstract class Singleton
{
    private static array $instances = [];

    public static function instance(): self
    {
        if (!array_key_exists(static::class, static::$instances)) {
            static::$instances[static::class] = new static();
        }

        return static::$instances[static::class];
    }
}