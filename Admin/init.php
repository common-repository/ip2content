<?php

use WMIP2C\Admin\Modules\Conditions\ConditionsPage;

if (is_admin()) {
    (new ConditionsPage())->init();
}