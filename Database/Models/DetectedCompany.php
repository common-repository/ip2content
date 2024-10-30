<?php

declare (strict_types = 1);

namespace WMIP2C\Database\Models;

final class DetectedCompany
{
    private int $id;
    private string $name;
    private int $views;
    private string $branch;

    public function __construct(
        int $id,
        string $name,
        int $views,
        string $branch
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->views = $views;
        $this->branch = $branch;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function getBranch(): string
    {
        return $this->branch;
    }
}