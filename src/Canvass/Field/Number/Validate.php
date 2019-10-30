<?php

namespace Canvass\Field\Number;

use Canvass\Contract\FieldData;
use Canvass\Support\Validation\Builder;

final class Validate extends \Canvass\Field\AbstractField\Input\Validate
{
    protected $attributes_validation_rules = [
        'required' => ['required' => false,],
        'placeholder' => [
            'required' => false, 'allow_null' => true, 'data_type' => 'string',
        ],
        'step' => ['required' => false, 'numeric' => true,],
        'min' => ['required' => false, 'numeric' => true,],
        'max' => ['required' => false, 'numeric' => true,],
    ];

    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    ) {
        $builder = Builder::start()
            ->required($field->hasAttribute('required'))
            ->numeric();

        if ($field->hasAttribute('min')) {
            $builder->minValue($field->getAttribute('min'));
        }

        if ($field->hasAttribute('max')) {
            $builder->maxValue($field->getAttribute('max'));
        }

        $rules[$field['name']] = [
            'field' => $field,
            'rules' => $builder->build()
        ];
    }

    public function convertAttributesData($attributes): array
    {
        $return = [];
        
        if (! empty($attributes['required'])) {
            $return['required'] = 'required';
        }
        
        if (! empty($attributes['placeholder'])) {
            $return['placeholder'] = $attributes['placeholder'];
        }
        
        if (! empty($attributes['min'])) {
            $return['min'] = (int) $attributes['min'];
        }
        
        if (! empty($attributes['max'])) {
            $return['max'] = (int) $attributes['max'];
        }

        if (! empty($attributes['step'])) {
            $return['step'] = (int) $attributes['step'];
        }
        
        return $return;
    }
}
