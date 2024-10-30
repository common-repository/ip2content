<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Mapper;

use WMIP2C\Http\Data\PaginationData;
use WP_REST_Request;

final class PaginationDataMapper
{
    public function mapRequestToData(WP_REST_Request $request): PaginationData
    {
        return new PaginationData(
            (int) $request->get_query_params()['page'],
            (int) $request->get_query_params()['per_page']
        );
    }

    public function mapDataToArray(PaginationData $paginationData): array
    {
        return [
            'page' => $paginationData->getPage(),
            'per_page' => $paginationData->getPerPage(),
            'last_page' => $paginationData->getLastPage(),
            'total_items' => $paginationData->getTotalItems(),
        ];
    }
}
