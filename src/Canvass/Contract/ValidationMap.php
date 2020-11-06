<?php

namespace Canvass\Contract;

use WebAnvil\Interfaces\ValidationMapInterface;

interface ValidationMap extends ValidationMapInterface
{
    public function convertRulesToFormat($rules);
}
