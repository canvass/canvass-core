<?php

namespace Canvass\Field\Option;

use Canvass\Contract\FieldData;
use Canvass\Field\AbstractField\AbstractValidateFieldAction;

final class Validate extends AbstractValidateFieldAction
{
    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    ) {
        return null;
    }

    public function getDataColumnsMatchedWithRequiredBoolean(): array
    {
        return [
            'label' => true,
            'value' => true,
        ];
    }
}
