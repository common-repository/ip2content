<?php

namespace WMIP2C\Http\Exceptions;

use Exception;

final class NotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct("", 404);
    }
}