<?php

namespace WMIP2C\Database\Models;

final class Condition
{
    public int $id;
    private string $label;
    private bool $active;
    private int $field_id;
    private int $hits;
    private int $default_content_id;
    private string $created_at;
    private string $updated_at;

    /**
     * @var ConditionCombination[]
     */
    private array $combinations;

    public function __construct(
        int $id,
        string $label,
        bool $active,
        int $field_id,
        int $default_content_id,
        int $hits,
        string $created_at,
        string $updated_at,
        array $combinations = []
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->active = $active;
        $this->field_id = $field_id;
        $this->default_content_id = $default_content_id;
        $this->hits = $hits;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->combinations = $combinations;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getFieldId(): int
    {
        return $this->field_id;
    }

    public function getDefaultContentId(): int
    {
        return $this->default_content_id;
    }

    public function getHits(): int
    {
        return $this->hits;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function getCombinations(): array
    {
        return $this->combinations;
    }

    public function setCombinations(array $combinations): void
    {
        $this->combinations = $combinations;
    }
}
