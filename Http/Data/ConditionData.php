<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Data;

final class ConditionData
{
    private int $id;
    private string $name;
    private string $fieldAlias;
    private bool $active;
    private string $updatedAt;

    public function __construct(
        int $id,
        string $name,
        string $fieldAlias,
        bool $active,
        string $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->fieldAlias = $fieldAlias;
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

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }
}