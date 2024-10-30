<?php

namespace WMIP2C\Http\Requests;

use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

abstract class Request extends Validation
{
    public function __construct($data)
    {
        parent::__construct(
            new Validator([
                'required' => 'This field is required'
            ]),
            $data,
            $this->rules()
        );
    }

    abstract public function rules(): array;
}