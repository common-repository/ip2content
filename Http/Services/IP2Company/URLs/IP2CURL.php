<?php

declare(strict_types=1);

namespace WMIP2C\Http\Services\IP2Company\URLs;

final class IP2CURL extends URL
{
    private const PATTERN = '%s/%s/%s';

    private string $ip;

    public function __construct(string $host, string $token, string $ip)
    {
        parent::__construct($host, $token);

        $this->ip = $ip;
    }

    public function get(): string
    {
        return sprintf(self::PATTERN, $this->host, $this->token, $this->ip);
    }
}
