<?php

namespace Canvass\Contract;

interface FieldType
{
    public function getType(): string;

    public function getGeneralType(): string;
}
