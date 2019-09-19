<?php

include_once __DIR__ . '/../vendor/autoload.php';

if (! defined('CANVASS_BASE_DIR')) {
    define('CANVASS_BASE_DIR', dirname(__DIR__));
}

if (! defined('CANVASS_VIEW_DIR')) {
    define('CANVASS_VIEW_DIR', CANVASS_BASE_DIR . '/views');
}
