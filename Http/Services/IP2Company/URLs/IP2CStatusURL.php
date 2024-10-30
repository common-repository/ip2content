<?php

declare(strict_types=1);

namespace WMIP2C\Http\Services\IP2Company\URLs;

final class IP2CStatusURL extends URL
{
    private const PATTERN = '%s/%s/status';

    public function get(): string
    {
        return sprintf(self::PATTERN, $this->host, $this->token);
    }
}
