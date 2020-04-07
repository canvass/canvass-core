<?php

namespace Implement;

use WebAnvil\Interfaces\ValidatorInterface;

class Validate implements \Canvass\Contract\Validate, ValidatorInterface
{
    public function validate($data, $rules)
    {
        return true;
    }
}
