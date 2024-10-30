<?php

namespace WMIP2C\Common\Enums;

final class WordpressActions
{
    public const ENQUE_SCRIPTS = 'wp_enqueue_scripts';
    public const ADMIN_ENQUE_SCRIPTS = 'admin_enqueue_scripts';
    public const ADMIN_INIT = 'admin_init';
    public const ADMIN_MENU = 'admin_menu';
    public const WP_LOADED = 'wp_loaded';
}