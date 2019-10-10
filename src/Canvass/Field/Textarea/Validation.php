<?php

namespace Canvass\Field\Textarea;

use Canvass\Contract\FieldData;

final class Validation extends \Canvass\Field\AbstractField\Input\Validation
{
    private const WRAP_VALUES = ['hard', 'soft', 'off'];

    protected $attributes_validation_rules = [
        'required' => ['required' => false,],
        'minlength' => ['required' => false, 'numeric' => true,],
        'maxlength' => ['required' => false, 'numeric' => true,],
        'rows' => ['required' => false, 'numeric' => true,],
        'cols' => ['required' => false, 'numeric' => true,],
        'placeholder' => ['required' => false, 'max_length' => 160,],
        'wrap' => ['required' => false, 'one_of' => self::WRAP_VALUES,],
    ];

    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    )
    {
        self::populateTextBasedFieldRules(
            $field,
            $rules
        );
    }

    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'name' => true,
            'label' => true,
            'identifier' => true,
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

        if (! empty($attributes['minlength'])) {
            $return['minlength'] = (int) $attributes['minlength'];
        }

        if (! empty($attributes['maxlength'])) {
            $return['maxlength'] = (int) $attributes['maxlength'];
        }

        if (! empty($attributes['rows'])) {
            $return['rows'] = (int) $attributes['rows'];
        }

        if (! empty($attributes['cols'])) {
            $return['cols'] = (int) $attributes['cols'];
        }

        if (
            ! empty($attributes['wrap']) &&
            in_array($attributes['wrap'], self::WRAP_VALUES, true)
        ) {
            $return['wrap'] = $attributes['wrap'];
        }

        return $return;
    }
}
