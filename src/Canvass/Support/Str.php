<?php

namespace Canvass\Support;

class Str
{
    public static function classSegment(string $string): string
    {
        return str_replace(['-', ' '], '', ucwords($string, '- '));
    }

    public static function slug(string $string = null): string
    {
        if (null === $string) {
            return '';
        }

        return preg_replace(
            '/[^A-z0-9]$/',
            '',
            preg_replace(
                '/\-+/','-',
                preg_replace(
                    '/[^a-z0-9]/',
                    '-',
                    strtolower(
                        trim(
                            str_replace(["'", '"'], '', $string)
                        )
                    )
                )
            )
        );
    }
}
