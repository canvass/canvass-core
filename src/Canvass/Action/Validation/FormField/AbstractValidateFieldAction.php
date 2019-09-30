<?php

namespace Canvass\Action\Validation\FormField;

use Canvass\Action\Validation\AbstractValidateDataAction;
use Canvass\Exception\InvalidValidationData;

abstract class AbstractValidateFieldAction extends AbstractValidateDataAction
    implements InterfaceValidateField
{
    private $rules;
    /** @var array */
    protected $attributes_validation_rules;

    public function __construct($validator, $validationConverter = null)
    {
        parent::__construct($validator, $validationConverter);

        $this->rules = [
            'name' => [
                'data_type' => 'string',
                'max_length' => 160,
                'required' => false,
            ],
            'label' => [
                'data_type' => 'string',
                'max_length' => 160,
                'required' => false,
            ],
            'identifier' => [
                'data_type' => 'string',
                'max_length' => 160,
                'required' => true,
            ],
            'classes' => [
                'data_type' => 'string',
                'max_length' => 160,
                'required' => false,
            ],
            'type' => [
                'data_type' => 'string',
                'max_length' => 160,
                'required' => true,
            ],
            'options' => [
                'data_type' => 'string',
                'max_length' => 1000,
                'required' => false,
            ],
            'value' => [
                'data_type' => 'string',
                'max_length' => 160,
                'required' => false,
            ],
            'help_text' => [
                'data_type' => 'string',
                'max_length' => 320,
                'required' => false,
            ],
        ];
    }

    public function getValidationRules(): array
    {
        $rules = [];

        foreach (static::getValidationKeysWithRequiredValue() as $rule => $required) {
            $rules = array_merge($rules, $this->getRule($rule, $required));
        }

        return $rules;
    }

    public static function getValidationKeysWithRequiredValue()
    {
        return [];
    }

    protected function getRule($name, bool $required): array
    {
        if (empty($this->rules[$name])) {
            throw new InvalidValidationData(
                "Could not find rules for {$name}"
            );
        }

        $rules = $this->rules[$name];

        $rules['required'] = $required;

        if (! $required) {
            $rules['allow_null'] = true;
        }

        return [$name => $rules];
    }

    public function validateAttributes($attributes): bool
    {
        if (null === $this->attributes_validation_rules) {
            throw new InvalidValidationData(
                'Attempting to validate attributes without any rules'
            );
        }

        return $this->validateDataWithRules(
            $attributes,
            $this->attributes_validation_rules
        );
    }

    public function hasValidatableAttributes(): bool
    {
        return ! empty($this->attributes_validation_rules);
    }

    public function convertAttributesData($attributes): array
    {
        return [];
    }
}
