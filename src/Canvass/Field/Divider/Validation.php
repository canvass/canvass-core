<?php

namespace Canvass\Field\Divider;

use Canvass\Contract\FieldData;

final class Validation extends \Canvass\Field\AbstractField\Input\Validation
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'identifier' => true,
            'classes' => false
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
