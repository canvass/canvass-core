<?php

namespace Canvass\Action\Validation\FormField;

use Canvass\Support\FieldData;
use Canvass\Support\Validation\Builder;

final class ValidateTimeField extends AbstractValidateInputField
{
    protected $attributes_validation_rules = [
        'required' => ['required' => false,],
        'placeholder' => [
            'required' => false, 'allow_null' => true, 'data_type' => 'string',
        ],
        'step' => [
            'required' => false,
            'numeric' => true,
            'max_length' => 86400, // 24 hours in seconds
        ],
        'min' => ['required' => false, 'date_format' => 'H:i:s',],
        'max' => ['required' => false, 'date_format' => 'H:i:s',],
    ];

    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    ) {
        $builder = Builder::start()
            ->required($field->hasAttribute('required'))
            ->timeFormat();

        if ($field->hasAttribute('min')) {
            $builder->minTime($field->getAttribute('min'));
        }

        if ($field->hasAttribute('max')) {
            $builder->maxTime($field->getAttribute('max'));
        }

        $rules[$field['name']] = $builder->build();
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
            $return['min'] = $attributes['min'];
        }
        
        if (! empty($attributes['max'])) {
            $return['max'] = $attributes['max'];
        }

        if (! empty($attributes['step'])) {
            $return['step'] = (int) $attributes['step'];
        }
        
        return $return;
    }
}
