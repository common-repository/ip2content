<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Data;

final class ConditionCombinationsEditData
{
    private int $id;
    private int $conditionId;
    private ?int $contentId;
    private int $operatorId;
    private string $value;
    private ?string $countries;
    private ?string $utmTag;

    public function __construct(
        int $id,
        int $conditionId,
        int $operatorId,
        ?int $contentId,
        string $value,
        ?string $countries = null,
        ?string $utmTag = null
    ) {
        $this->id = $id;
        $this->conditionId = $conditionId;
        $this->contentId = $contentId;
        $this->operatorId = $operatorId;
        $this->value = $value;
        $this->countries = $countries;
        $this->utmTag = $utmTag;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getConditionId(): int
    {
        return $this->conditionId;
    }

    public function getContentId(): ?int
    {
        return $this->contentId;
    }

    public function getOperatorId(): int
    {
        return $this->operatorId;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCountries(): ?string
    {
        return $this->countries;
    }

    public function getUtmTag(): ?string
    {
        return $this->utmTag;
    }
}