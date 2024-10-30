<?php

namespace WMIP2C\Http\Requests;

final class ConditionEditRequest extends Request
{
    public function rules(): array
    {
        return [
            'label'              => 'required|min:5|max:255',
            'active'             => 'boolean|default:0',
            'field_id'           => 'required|integer',
            'operator_id'        => 'integer',
            'content_id'         => 'integer',
            'default_content_id' => 'integer',
        ];
    }

    protected $aliases = [
        'field_id' => "Field"
    ];
}
