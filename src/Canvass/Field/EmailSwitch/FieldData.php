<?php

namespace Canvass\Field\EmailSwitch;

use Canvass\Contract\FieldDataRetrievable;

final class FieldData extends \Canvass\Support\FieldData
    implements FieldDataRetrievable
{
    /** @return \Canvass\Contract\FieldDataRetrievable */
    public function retrieveAdditionalData()
    {
        return $this;
    }
}
