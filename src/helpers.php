<?php

if (! function_exists('canvass_core_path')) {
    function canvass_core_path($path) {
        return dirname(__DIR__, 1) . '/' . ltrim($path, '/');
    }
}

if (! function_exists('e')) {
    function e(string $value, bool $doubleEncode = true)
    {
        return htmlspecialchars(
            $value,
            ENT_QUOTES,
            'UTF-8',
            $doubleEncode
        );
    }
}

if (! function_exists('show')) {
    function show(string $value, bool $doubleEncode = true)
    {
        echo e($value, $doubleEncode);
    }
}
