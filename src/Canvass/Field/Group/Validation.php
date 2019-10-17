<?php

namespace Canvass\Field\Group;

use Canvass\Contract\FieldData;
use Canvass\Support\Validation\Builder;

final class Validation extends \Canvass\Field\AbstractField\Input\Validation
{
    protected $attributes_validation_rules = [
        'required' => ['required' => false,],
    ];

    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    )
    {
        $is_checkbox = 'checkbox-group' === $field['type'];

        $builder = Builder::start()
            ->required($field->hasAttribute('required'));

        foreach ($field['children'] as $child) {
            if ($is_checkbox) {
                $builder->addValueToInGroup($child['value']);
            } else {
                $builder->addOneOf($child['value']);
            }
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
            'type' => true,
            'classes' => false,
            'wrap_classes' => false,
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
