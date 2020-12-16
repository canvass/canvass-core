<?php

namespace Implement;

class Validate implements \Canvass\Contract\Validate
{
    public function validate($data, $rules)
    {
        return true;
    }

    public function getErrors(): array
    {
        return [];
    }

    public function getErrorsString(): string
    {
        return '';
    }

}
