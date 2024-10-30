<?php

namespace WMIP2C\Http\Requests;

class ConditionGetOneRequest extends Request
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
        ];
    }
}
