<?php

namespace WMIP2C\Common\Services;

use Exception;

/**
 * @method static PluginUrls instance;
 */
final class PluginUrls
{
    use SingletonTrait;

    protected string $rootUrl;
    protected string $scriptsDirName = 'js/';
    protected string $assetsDirName = 'assets/';
    protected string $stylesDirName = 'styles/';
    protected string $imagesDirName = 'images/';

    /**
     * @throws Exception
     */
    public function setRootUrl(string $pluginFile): self
    {
        if (!file($pluginFile)) {
            throw new Exception();
        }

        $this->rootUrl = plugin_dir_url($pluginFile);

        return $this;
    }

    public static function getScriptsUrl($scriptFile = ''): string
    {
        $instance = self::instance();

        return $instance->rootUrl . $instance->assetsDirName . $instance->scriptsDirName . $scriptFile;
    }

    public static function getImagesUrl($imageFile = ''): string
    {
        $instance = self::instance();

        return $instance->rootUrl . $instance->assetsDirName . $instance->imagesDirName . $imageFile;
    }

    public static function getStylesUrl($styleFile = ''): string
    {
        $instance = self::instance();

        return $instance->rootUrl . $instance->assetsDirName . $instance->stylesDirName . $styleFile;
    }
}