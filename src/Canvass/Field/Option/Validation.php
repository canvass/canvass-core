<?php

namespace Canvass\Field\Option;

use Canvass\Contract\FieldData;

final class Validation extends \Canvass\Field\AbstractField\Input\Validation
{
    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    ) {
        return null;
    }

    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'label' => true,
            'value' => true,
        ];
    }
}
