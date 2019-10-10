<?php

namespace Canvass\Support;

class Str
{
    public static function camelCase(string $string): string
    {
        return str_replace('-', '', ucwords($string, '-'));
    }
}
