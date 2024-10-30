<?php

namespace WMIP2C\Database\Models;

final class ConditionCombination
{
    private int $id;
    private int $condition_id;
    private string $value;
    private int $operator_id;
    private int $content_id;
    private int $order;

    public function __construct(
        int $id,
        int $condition_id,
        int $operator_id,
        int $content_id,
        string $value,
        int $order
    ) {
        $this->id = $id;
        $this->condition_id = $condition_id;
        $this->value = $value;
        $this->operator_id = $operator_id;
        $this->content_id = $content_id;
        $this->order = $order;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getConditionId(): int
    {
        return $this->condition_id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getOperatorId(): int
    {
        return $this->operator_id;
    }

    public function getContentId(): int
    {
        return $this->content_id;
    }

    public function getOrder(): int
    {
        return $this->order;
    }
}