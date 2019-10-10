<?php

namespace Canvass\Field\AbstractField;

use Canvass\Action\Validation\AbstractValidateDataAction;
use Canvass\Action\Validation\FormField\InterfaceValidateField;
use Canvass\Exception\InvalidValidationData;
use Canvass\Contract\FieldData;
use Canvass\Support\Validation\Builder;

abstract class AbstractValidateFieldAction extends AbstractValidateDataAction
    implements InterfaceValidateField
{
    private $rules;
    /** @var array */
    protected $attributes_validation_rules;

    public function __construct($validator, $validationConverter = null)
    {
        parent::__construct($validator, $validationConverter);

        $default_rules = Builder::start()->isStringType()->optional();

        $rules_160 = $default_rules->maxLength(160)->build();

        $this->rules = [
            'name' => $rules_160,
            'label' => $rules_160,
            'identifier' => $rules_160,
            'classes' => $rules_160,
            'type' => $rules_160,
            'general_type' => $rules_160,
            'value' => $rules_160,
            'options' => $default_rules->maxLength(1000)->build(),
            'help_text' => $default_rules->maxLength(320)->build(),
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

    abstract public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    );

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
