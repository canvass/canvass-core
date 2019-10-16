<?php

namespace Canvass\Field\Divider;

use Canvass\Contract\FieldData;
use Canvass\Field\AbstractField\AbstractValidateFieldAction;

final class Validation extends AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'identifier' => true,
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
