<?php

namespace Canvass\Field\Select;

use Canvass\Contract\FieldData;
use Canvass\Field\AbstractField\AbstractValidateFieldAction;
use Canvass\Support\Validation\Builder;

final class Validation extends AbstractValidateFieldAction
{
    protected $attributes_validation_rules = [
        'required' => ['required' => false,],
    ];

    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    )
    {
        $builder = Builder::start()
            ->required($field->hasAttribute('required'));

        foreach ($field['children'] as $child) {
            $builder->addOneOf($child['value']);
        }

        $rules[$field['name']] = [
            'field' => $field,
            'rules' => $builder->build()
        ];
    }

    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'name' => true,
            'label' => true,
            'identifier' => true,
            'classes' => false,
        ];
    }

    public function convertAttributesData($attributes): array
    {
        $return = [];

        if (! empty($attributes['required'])) {
            $return['required'] = 'required';
        }

        return $return;
    }
}
