<?php

namespace Canvass\Contract;

use WebAnvil\Interfaces\ValidatorInterface;

interface Validate extends ValidatorInterface
{
    public function validate($data, $rules);
}
