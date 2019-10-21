<?php

namespace Canvass\Support;

class Str
{
    public static function classSegment(string $string): string
    {
        return str_replace(['-', ' '], '', ucwords($string, '- '));
    }
}
