<?php

namespace WMIP2C\Http\Services\IP2Company;

use WMIP2C\Common\Enums\WordpressOptions;
use WMIP2C\Http\Exceptions\IP2CompanyClientException;
use WMIP2C\Http\Exceptions\IP2CompanyException;
use WMIP2C\Http\Exceptions\IP2CompanyForbiddenException;
use WMIP2C\Http\Exceptions\IP2CompanyNotFoundException;
use WMIP2C\Http\Services\CacheService;
use WMIP2C\Http\Services\IP2Company\URLs\IP2CStatusURL;
use WMIP2C\Http\Services\IP2Company\URLs\IP2CURL;
use WMIP2C\Http\Services\IP2Company\URLs\URL;

final class IP2CompanyClient implements CompanyParser
{
    private const IP2C_HOST = 'https://ip2c.wiredminds.com';

    private ?string $ip2cToken;
    private IPResolver $ipResolver;
    private array $companiesInMemoryCache = [];
    private CacheService $cacheService;

    public function __construct()
    {
        $this->ip2cToken = get_option(WordpressOptions::IP2C_TOKEN, null);
        $this->ipResolver = new IPResolver();

        $this->cacheService = new CacheService();
    }

    /**
     * @throws IP2CompanyException
     */
    public function getCompanyByIP(): array
    {
        $clientIP = $this->ipResolver->resolveClientIP();

        if (isset($this->companiesInMemoryCache[$clientIP])) {
            if ($this->companiesInMemoryCache[$clientIP] === []) {
                throw new IP2CompanyNotFoundException();
            }

            return $this->companiesInMemoryCache[$clientIP];
        }

        $clientCompany = $this->cacheService->get(sprintf('client.%s', $clientIP));

        if ($clientCompany === null) {
            $url = new IP2CURL(self::IP2C_HOST, $this->ip2cToken, $clientIP);

            try {
                $body = $this->sendRequest($url);
                $this->cacheService->set(sprintf('client.%s', $clientIP), serialize($body));
            } catch (IP2CompanyException $exception) {
                $this->companiesInMemoryCache[$clientIP] = [];

                throw $exception;
            }
        } else {
            $body = unserialize($clientCompany);
        }

        return $body;
    }

    public function getIP2CompanyStatus(): array
    {
        $url = new IP2CStatusURL(self::IP2C_HOST, $this->ip2cToken);

        try {
            $response = $this->sendRequest($url);
        } catch (IP2CompanyException $exception) {
            return [];
        }

        return $response[0] ?? [];
    }

    /**
     * @throws IP2CompanyException
     */
    private function sendRequest(URL $url): array
    {
        if ($this->ip2cToken === null) {
            throw new IP2CompanyClientException('IP2C token not found');
        }

        $response = wp_remote_get($url->get());
        $responseCode = wp_remote_retrieve_response_code($response);

        if ($responseCode === 404) {
            throw new IP2CompanyNotFoundException();
        }

        if ($responseCode === 403) {
            throw new IP2CompanyForbiddenException();
        }

        $bodyJson = wp_remote_retrieve_body($response);

        if ($responseCode !== 200 || $bodyJson === '') {
            throw new IP2CompanyClientException('Cannot parse response body');
        }

        return json_decode($bodyJson, true, 512, JSON_THROW_ON_ERROR);
    }
}
