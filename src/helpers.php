<?php

if (! function_exists('canvass_core_path')) {
    function canvass_core_path($path) {
        return dirname(__DIR__) . '/' . ltrim($path, '/');
    }
}
