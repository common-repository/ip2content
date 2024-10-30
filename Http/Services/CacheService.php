<?php

namespace WMIP2C\Http\Services;

use Flintstone\Flintstone;
use WMIP2C\Common\Enums\WordpressOptions;

final class CacheService
{
    private string $cacheDir;

    public function __construct()
    {
        $this->cacheDir = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/cache/' . WMIP2C_PLUGIN_PREFIX . '/';
    }

    public function get(string $key)
    {
        if ($this->checkIfCacheIsEnabled()) {
            return $this->getCacheEntry($key);
        }

        return null;
    }

    public function set(string $key, string $value)
    {
        if ($this->checkIfCacheIsEnabled()) {
            $this->setCacheEntry($key, $value);
        }
    }

    public function deleteCache(): void
    {
        $files = array_values(array_diff(scandir($this->cacheDir), array('..', '.')));

        foreach ($files as $file) {
            unlink($this->cacheDir . $file);
        }
    }

    private function checkIfCacheIsEnabled(): bool
    {
        $cachedSettings = $this->getCacheEntry('settings');

        if ($cachedSettings) {
            $settings = json_decode($cachedSettings, true);

            return $settings['cache'];
        }

        return (bool) get_option(WordpressOptions::CACHE_ACTIVE);
    }

    private function checkIfCacheIsExpired(string $key): bool
    {
        $cacheFileClient = $this->getCacheFileClient();

        $cached = $cacheFileClient->get($this->getCacheKey($key));
        $expired = false;

        if ($cached['ttl'] && $cached['ttl'] < strtotime("now")) {
            $expired = true;
        }

        return $expired;

    }

    private function getCacheEntry(string $key)
    {
        $cacheFileClient = $this->getCacheFileClient();

        $cached = $cacheFileClient->get($this->getCacheKey($key));

        if ($cached && $key !== 'settings') {
            if ($this->checkIfCacheIsExpired($key)) {
                $cached = null;
            }
        }

        if ($cached && $cached['data']) {
            return $cached['data'];
        }

        return null;
    }

    private function getCacheFileClient()
    {
        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }

        return new Flintstone(WMIP2C_PLUGIN_PREFIX . 'Cache', ['dir' => $this->cacheDir]);
    }

    private function getCacheKey(string $key): string
    {
        return md5($key);
    }

    private function setCacheEntry(string $key, string $data)
    {
        $cacheFileClient = $this->getCacheFileClient();

        $cached = array('data' => $data);

        if ($key !== 'settings') {
            $cached['ttl'] = strtotime('+7 days');
        }

        return $cacheFileClient->set($this->getCacheKey($key), $cached);
    }
}