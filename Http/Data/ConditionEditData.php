<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Data;

final class ConditionEditData
{
    private int $id;
    private ?int $defaultContentId;
    private string $label;
    private int $fieldId;
    private bool $active;

    /**
     * @var ConditionCombinationsEditData[]
     */
    private array $combinations;

    public function __construct(
        int $id,
        ?int $defaultContentId,
        string $label,
        int $fieldId,
        bool $active,
        array $combinations = []
    ) {
        $this->id = $id;
        $this->defaultContentId = $defaultContentId;
        $this->label = $label;
        $this->fieldId = $fieldId;
        $this->active = $active;
        $this->combinations = $combinations;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDefaultContentId(): ?int
    {
        return $this->defaultContentId;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getFieldId(): int
    {
        return $this->fieldId;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return ConditionCombinationsEditData[]
     */
    public function getCombinations(): array
    {
        return $this->combinations;
    }
}