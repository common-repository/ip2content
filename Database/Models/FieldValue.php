<?php

namespace WMIP2C\Database\Models;

use WMIP2C\Common\Enums\WordpressOptions;

final class FieldValue
{
    private const DEFAULT_LANGUAGE = 'en';
    public const MULTISELECT_SEPARATOR = ',';

    private int $id;
    private string $value;
    private string $name_en;
    private string $name_de;
    private int $field_id;

    public function __construct(
        int $id,
        string $value,
        string $nameEn,
        string $nameDe,
        int $fieldId
    ) {
        $this->id = $id;
        $this->value = $value;
        $this->name_en = $nameEn;
        $this->name_de = $nameDe;
        $this->field_id = $fieldId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
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

    public function getFieldId(): int
    {
        return $this->field_id;
    }
}
