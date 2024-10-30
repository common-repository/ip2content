<?php

declare(strict_types=1);

namespace WMIP2C\Http\Controllers\Api;

use WMIP2C\Http\Services\Conditions\ConditionFieldsManager;
use WMIP2C\Http\Services\Conditions\FieldOperatorsManager;
use WMIP2C\Http\Services\Conditions\FieldValuesManager;
use WMIP2C\Http\Services\HttpResponse;
use WP_REST_Request;
use WP_REST_Response;

final class ConditionFieldsApiController extends UserAwareController
{
    private FieldValuesManager $fieldValuesManager;
    private FieldOperatorsManager $fieldOperatorsManager;
    private ConditionFieldsManager $conditionFieldsManager;

    public function __construct()
    {
        $this->fieldValuesManager = new FieldValuesManager();
        $this->fieldOperatorsManager = new FieldOperatorsManager();
        $this->conditionFieldsManager = new ConditionFieldsManager();
    }

    public function getList(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        $fieldsList = $this->conditionFieldsManager->getFieldsList();

        return HttpResponse::successful($fieldsList);
    }

    public function getFieldOperatorsList(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        $fieldId = (int)$request->get_param('id');
        $fieldsList = $this->fieldOperatorsManager->getOperatorsListByField($fieldId);

        return HttpResponse::successfulCreated($fieldsList);
    }

    public function getFieldValuesList(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        $fieldId = (int)$request->get_param('id');
        $valuesList = $this->fieldValuesManager->getValuesListByField($fieldId);

        return HttpResponse::successfulCreated($valuesList);
    }
}