<?php

namespace WMIP2C\Http\Controllers\Api;

use Exception;
use Throwable;
use WMIP2C\Http\Exceptions\ConditionNotFoundException;
use WMIP2C\Http\Exceptions\FieldNotFoundException;
use WMIP2C\Http\Exceptions\UnprocessableEntityException;
use WMIP2C\Http\Mapper\ConditionEditDataMapper;
use WMIP2C\Http\Mapper\ConditionsPaginationDataMapper;
use WMIP2C\Http\Mapper\PaginationDataMapper;
use WMIP2C\Http\Requests\ConditionEditRequest;
use WMIP2C\Http\Requests\ConditionGetOneRequest;
use WMIP2C\Http\Services\CacheService;
use WMIP2C\Http\Services\Conditions\ConditionManager;
use WMIP2C\Http\Services\HttpResponse;
use WMIP2C\Http\Services\RequestValidationService;
use WP_REST_Request;
use WP_REST_Response;

final class ConditionsApiController extends UserAwareController
{
    private ConditionManager $conditionManager;
    private PaginationDataMapper $paginationDataMapper;
    private ConditionsPaginationDataMapper $conditionsPaginationDataMapper;
    private ConditionEditDataMapper $conditionEditDataMapper;

    public function __construct()
    {
        $this->conditionManager = new ConditionManager();
        $this->paginationDataMapper = new PaginationDataMapper();
        $this->conditionsPaginationDataMapper = new ConditionsPaginationDataMapper();
        $this->conditionEditDataMapper = new ConditionEditDataMapper();
    }

    public function index(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        try {
            $paginationData = $this->paginationDataMapper->mapRequestToData($request);

            $conditionsPaginationData = $this->conditionManager->getAllConditionsPaginated($paginationData);
            $paginatedConditions = $this->conditionsPaginationDataMapper->mapDataToArray($conditionsPaginationData);
        } catch (Throwable $t) {
            return HttpResponse::failed();
        }

        return HttpResponse::successful($paginatedConditions);
    }

    public function store(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        try {
            (new RequestValidationService())->validate($request, ConditionEditRequest::class);
        } catch (UnprocessableEntityException $exception) {
            return HttpResponse::failedValidationError($exception->errors);
        }

        try {
            $attributes = $request->get_params();
            $this->conditionManager->createCondition($attributes);

            return HttpResponse::successfulCreated();
        } catch (FieldNotFoundException $exception) {
            return HttpResponse::failed(['error' => 'Undefined field']);
        } catch (Throwable $throwable) {
            return HttpResponse::failed();
        }
    }

    public function edit(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        try {
            (new RequestValidationService())->validate($request, ConditionGetOneRequest::class);
        } catch (UnprocessableEntityException $exception) {
            return HttpResponse::failedValidationError($exception->errors);
        }

        try {
            $conditionId = (int)$request->get_param('id');
            $conditionEditData = $this->conditionManager->getConditionForEditById($conditionId);

            $condition = $this->conditionEditDataMapper->mapDataToArray($conditionEditData);

            return HttpResponse::successful($condition);
        } catch (ConditionNotFoundException $exception) {
            return HttpResponse::failedNotFound();
        } catch (Exception $exception) {
            return HttpResponse::failed();
        }
    }

    public function update(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        try {
            (new RequestValidationService())->validate($request, ConditionEditRequest::class);
        } catch (UnprocessableEntityException $exception) {
            return HttpResponse::failedValidationError($exception->errors);
        }

        try {
            $attributes = $request->get_params();
            $conditionId = (int)$attributes['id'];

            $this->conditionManager->updateConditionById($conditionId, $attributes);

            return HttpResponse::successfulNoContent();
        } catch (ConditionNotFoundException $exception) {
            return HttpResponse::failedNotFound();
        } catch (Exception $exception) {
            return HttpResponse::failed();
        }
    }

    public function delete(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        try {
            (new RequestValidationService())->validate($request, ConditionGetOneRequest::class);
        } catch (UnprocessableEntityException $exception) {
            return HttpResponse::failedValidationError($exception->errors);
        }

        $conditionId = $request->get_param('id');

        try {
            $this->conditionManager->deleteConditionById($conditionId);

            return HttpResponse::successfulNoContent();
        } catch (ConditionNotFoundException $exception) {
            return HttpResponse::failedNotFound();
        } catch (Exception $exception) {
            return HttpResponse::failed();
        }
    }

    public function updateStatus(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        try {
            $conditionId = $request->get_param('id');
            $status = (bool) $request->get_param('active');

            $this->conditionManager->toggleConditionState($conditionId, $status);

            return HttpResponse::successfulNoContent();
        } catch (ConditionNotFoundException $exception) {
            return HttpResponse::failedNotFound();
        } catch (Exception $exception) {
            return HttpResponse::failed();
        }
    }
}
