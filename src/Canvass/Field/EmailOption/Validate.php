<?php

namespace Canvass\Field\EmailOption;

use Canvass\Contract\FieldData;
use Canvass\Field\AbstractField\AbstractValidateFieldAction;

final class Validate extends AbstractValidateFieldAction
{
    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    )
    {
        return null;
    }

    public function getDataColumnsMatchedWithRequiredBoolean(): array
    {
        return [
            'name' => true,
            'label' => true,
            'value' => true,
        ];
    }
}
