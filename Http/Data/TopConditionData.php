<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Data;

final class TopConditionData
{
    private int $id;
    private string $name;
    private string $fieldAlias;
    private int $hits;
    private bool $active;
    private string $updatedAt;

    public function __construct(
        int $id,
        string $name,
        string $fieldAlias,
        int $hits,
        bool $active,
        string $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->fieldAlias = $fieldAlias;
        $this->hits = $hits;
        $this->active = $active;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFieldAlias(): string
    {
        return $this->fieldAlias;
    }

    public function getHits(): int
    {
        return $this->hits;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }
}