<?php

namespace Canvass\Field\Divider;

use Canvass\Contract\FieldData;
use Canvass\Field\AbstractField\AbstractValidateFieldAction;

final class Validate extends AbstractValidateFieldAction
{
    public function getDataColumnsMatchedWithRequiredBoolean(): array
    {
        return [
            'identifier' => true,
            'wrap_classes' => false,
            'classes' => false,
            'label' => false,
            'help_text' => false,
        ];
    }

    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    )
    {
        return null;
    }
}
