<?php

namespace Canvass\Contract;

use WebAnvil\Interfaces\ValidatorInterface;

interface Validate extends ValidatorInterface
{
    public function validate($data, $rules);

    public function getErrors(): array;

    public function getErrorsString(): string;
}
