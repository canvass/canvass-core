<?php

namespace Canvass\Field\AbstractField\Toggle;

use Canvass\Contract\FieldData;
use Canvass\Support\Validation\Builder;

abstract class ChildFieldValidation
    extends \Canvass\Field\AbstractField\AbstractValidateFieldAction
{
    protected $attributes_validation_rules = [
        'checked' => ['checked' => false,],
    ];

    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    )
    {
        $rules[$field['name']] = Builder::start()
            ->optional()
            ->isValue($field['value'])
            ->build();
    }

    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'label' => true,
            'identifier' => true,
            'value' => true,
        ];
    }

    public function convertAttributesData($attributes): array
    {
        $return = [];

        if (! empty($attributes['checked'])) {
            $return['checked'] = 'checked';
        }

        return $return;
    }
}
