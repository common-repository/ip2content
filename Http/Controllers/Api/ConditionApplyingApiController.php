<?php

declare(strict_types=1);

namespace WMIP2C\Http\Controllers\Api;

use Throwable;
use WMIP2C\Http\Mapper\ConditionContentDataMapper;
use WMIP2C\Http\Mapper\ConditionSourcesDataMapper;
use WMIP2C\Http\Services\ConditionsWorkflow\ConditionReactorBuilder;
use WMIP2C\Http\Services\HttpResponse;
use WP_REST_Request;
use WP_REST_Response;

final class ConditionApplyingApiController
{
    private ConditionReactorBuilder $conditionReactorBuilder;
    private ConditionSourcesDataMapper $conditionSourcesDataMapper;
    private ConditionContentDataMapper $conditionContentBagMapper;

    public function __construct()
    {
        $this->conditionReactorBuilder = new ConditionReactorBuilder();
        $this->conditionSourcesDataMapper = new ConditionSourcesDataMapper();
        $this->conditionContentBagMapper = new ConditionContentDataMapper();
    }

    public function applyConditions(WP_REST_Request $request): WP_REST_Response
    {
        $ids = $request->get_param('ids');

        if (empty($ids)) {
            return HttpResponse::successful([]);
        }

        $conditionsIds = $this->normalizeConditionIds($ids);
        $conditionSourcesData = $this->conditionSourcesDataMapper->mapRequestToData($request);

        try {
            $userId = $conditionSourcesData->getUserId();

            $conditionReactor = $this->conditionReactorBuilder->build($conditionSourcesData);
            $conditionContentBag = $conditionReactor->applyConditions($userId, $conditionsIds);

            $content = $this->conditionContentBagMapper->mapConditionContentBagToArray($conditionContentBag);

            return HttpResponse::successful($content);
        } catch (Throwable $throwable) {
            return HttpResponse::failed();
        }
    }

    private function normalizeConditionIds(string $ids): array
    {
        $conditionsIds = [];
        $conditionsIdsStrings = explode(',', $ids);

        foreach ($conditionsIdsStrings as $conditionsIdString) {
            if ($conditionsIdString === '') {
                continue;
            }

            $conditionsId = (int)$conditionsIdString;
            $conditionsIds[$conditionsId] = $conditionsId;
        }

        return $conditionsIds;
    }
}
