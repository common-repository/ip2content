<?php

declare(strict_types=1);

namespace WMIP2C\Http\Services\IP2Company\URLs;

abstract class URL
{
    protected string $host;
    protected string $token;

    public function __construct(string $host, string $token)
    {
        $this->host = $host;
        $this->token = $token;
    }

    abstract public function get(): string;
}