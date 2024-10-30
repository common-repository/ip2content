<?php

namespace WMIP2C\Http\Services;

use Rakit\Validation\ErrorBag;
use WMIP2C\Http\Exceptions\UnprocessableEntityException;
use WMIP2C\Http\Requests\Request;
use WP_REST_Request;

class RequestValidationService
{
    /**
     * @throws UnprocessableEntityException
     */
    public function validate(WP_REST_Request $request, string $requestValidationClass): void
    {
        /** @var Request $validation */
        $validation = new $requestValidationClass($request->get_params());
        $validation->validate();

        if ($validation->fails()) {
            throw new UnprocessableEntityException($this->prepareErrors($validation->errors()));
        }
    }

    private function prepareErrors(ErrorBag $errorBag): array
    {
        return array_map(static fn($errorMessages) => array_values($errorMessages), $errorBag->toArray());
    }
}