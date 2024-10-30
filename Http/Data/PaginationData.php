<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Data;

final class PaginationData
{
    private const DEFAULT_PER_PAGE = 10;

    private int $page;
    private int $perPage;
    private int $lastPage;
    private int $totalItems;

    public function __construct(
        int $page = 0,
        int $perPage = 0,
        int $lastPage = 0,
        int $totalItems = 0
    ) {
        $this->page = $this->normalizePage($page);
        $this->perPage = $this->normalizePerPage($perPage);
        $this->lastPage = $lastPage;
        $this->totalItems = $totalItems;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getOffset(): int
    {
        return ($this->page - 1) * $this->perPage;
    }

    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    private function normalizePage(int $page): int
    {
        if ($page <= 0) {
            return 1;
        }

        return $page;
    }

    private function normalizePerPage(int $perPage): int
    {
        if ($perPage <= 0) {
            return self::DEFAULT_PER_PAGE;
        }

        return $perPage;
    }
}
