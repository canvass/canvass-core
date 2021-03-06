<?php

namespace Canvass\Field\Group;

use Canvass\Contract\FieldData;
use Canvass\Field\AbstractField\AbstractValidateFieldAction;
use Canvass\Support\Validation\Builder;

class Validate extends AbstractValidateFieldAction
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

    public function getDataColumnsMatchedWithRequiredBoolean(): array
    {
        return [
            'name' => true,
            'label' => true,
            'identifier' => true,
            'type' => false,
            'classes' => false,
            'wrap_classes' => false,
            'help_text' => false,
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
