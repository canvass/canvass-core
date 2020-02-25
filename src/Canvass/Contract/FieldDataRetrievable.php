<?php

namespace Canvass\Contract;

interface FieldDataRetrievable extends FieldData
{
    /** @return self|static */
    public function retrieveAdditionalData();
}
