<?php

namespace Canvass\Contract;

interface FieldDataRetrievable extends FieldData
{
    public function retrieveAdditionalData(): self;
}
