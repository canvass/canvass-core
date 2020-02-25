<?php

namespace Canvass\Contract;

interface FieldType
{
    /** @return string */
    public function getType();
    /** @return string */
    public function getGeneralType();
}
