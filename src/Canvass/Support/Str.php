<?php

namespace Canvass\Support;

class Str
{
    /**
     * @param string $string
     * @return string
     */
    public static function classSegment($string)
    {
        return str_replace(['-', ' '], '', ucwords($string, '- '));
    }

    /**
     * @param string $string
     * @return string
     */
    public static function slug($string = null)
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
