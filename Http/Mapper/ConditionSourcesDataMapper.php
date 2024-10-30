<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Mapper;

use WMIP2C\Http\Data\ConditionSourcesData;
use WP_REST_Request;

final class ConditionSourcesDataMapper
{
    public function mapRequestToData(WP_REST_Request $request): ConditionSourcesData
    {
        $attributes = $request->get_attributes();

        return new ConditionSourcesData(
            (int) $attributes['user_id'],
            $request->get_param('query'),
        );
    }
}
