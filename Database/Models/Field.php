<?php

namespace WMIP2C\Database\Models;

final class Field
{
    private int $id;
    private string $alias;
    private string $source;
    private int $default_operator_id;

    public function __construct(
        int $id,
        string $alias,
        string $source,
        int $default_operator_id
    ) {
        $this->id = $id;
        $this->alias = $alias;
        $this->source = $source;
        $this->default_operator_id = $default_operator_id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getDefaultOperatorId(): int
    {
        return $this->default_operator_id;
    }
}