<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\IP2Company;

interface CompanyParser
{
    public function getCompanyByIP(): array;
}