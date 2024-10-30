<?php

use WMIP2C\Common\CustomPostTypes\Conditions\ConditionsPostType;

add_action('init', function () {
    ConditionsPostType::instance()->register();
});