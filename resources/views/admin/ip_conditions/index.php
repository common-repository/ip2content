<?php

use WMIP2C\Common\Enums\EnqueuedScriptAliases;
use WMIP2C\Common\Services\PluginUrls;

wp_enqueue_style(EnqueuedScriptAliases::IP_CONDITIONS_CSS);
wp_enqueue_script(EnqueuedScriptAliases::IP_CONDITIONS_VUE);
wp_localize_script(EnqueuedScriptAliases::IP_CONDITIONS_VUE, 'ipConditionsData', [
    'imagesUrl' => PluginUrls::getImagesUrl(),
]);
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<div id="ip_conditions" class="wrap">
    <div id="ip_conditions_vue"></div>
</div>