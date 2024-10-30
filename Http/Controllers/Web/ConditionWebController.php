<?php

namespace WMIP2C\Http\Controllers\Web;

use WMIP2C\Http\Controllers\Controller;

/**
 * @method static ConditionWebController instance
 */
final class ConditionWebController extends Controller
{
    public function index()
    {
        ob_start();

        require WMIP2C_ROOT_DIR . 'resources/views/admin/ip_conditions/index.php';

        return ob_get_clean();
    }
}