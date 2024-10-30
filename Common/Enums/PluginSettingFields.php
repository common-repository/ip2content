<?php

namespace WMIP2C\Common\Enums;

use MyCLabs\Enum\Enum;

final class PluginSettingFields extends Enum
{
    public const HOST = WMIP2C_PLUGIN_PREFIX . '_host';
    public const TOKEN = WMIP2C_PLUGIN_PREFIX . '_token';
}