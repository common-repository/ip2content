<?php

namespace WMIP2C\Http\Exceptions;

use Exception;

final class UnprocessableEntityException extends Exception
{
    public array $errors = [];

    public function __construct($errors)
    {
        $this->errors = $errors;

        parent::__construct('', 422);
    }
}