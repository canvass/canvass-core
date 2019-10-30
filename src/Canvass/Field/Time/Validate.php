<?php

namespace Canvass\Field\Time;

use Canvass\Contract\FieldData;
use Canvass\Support\Validation\Builder;

final class Validate extends \Canvass\Field\AbstractField\Input\Validate
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
        'min' => ['required' => false, 'date_format' => 'H:i',],
        'max' => ['required' => false, 'date_format' => 'H:i',],
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
