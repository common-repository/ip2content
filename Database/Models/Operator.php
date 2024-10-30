<?php

namespace WMIP2C\Database\Models;

use WMIP2C\Common\Enums\WordpressOptions;

final class Operator
{
    private const DEFAULT_LANGUAGE = 'en';

    private int $id;
    private string $alias;
    private string $name_en;
    private string $name_de;
    private string $class;
    public function __construct(
        int $id,
        string $alias,
        string $nameEn,
        string $nameDe,
        string $class
    ) {
        $this->id = $id;
        $this->alias = $alias;
        $this->name_en = $nameEn;
        $this->name_de = $nameDe;
        $this->class = $class;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getName(): string
    {
        $language = get_option(WordpressOptions::LANGUAGE, self::DEFAULT_LANGUAGE);
        $nameProperty = "name_{$language}";

        if (property_exists(self::class, $nameProperty)) {
            return $this->{$nameProperty};
        }

        return $this->name_en ?: $this->name_de;
    }

    public function getClass(): string
    {
        return $this->class;
    }
}