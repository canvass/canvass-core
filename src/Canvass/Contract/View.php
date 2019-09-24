<?php

namespace Canvass\Contract;


interface View
{
    public function render($data = [], string $file = null): string;
}
