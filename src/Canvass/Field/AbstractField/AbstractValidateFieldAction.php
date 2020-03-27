<?php

namespace Canvass\Field\AbstractField;

use Canvass\Action\Validation\AbstractValidateDataAction;
use Canvass\Contract\Action\ValidateFieldAction;
use Canvass\Exception\InvalidValidationData;
use Canvass\Contract\FieldData;
use Canvass\Support\Validation\Builder;

abstract class AbstractValidateFieldAction extends AbstractValidateDataAction
    implements ValidateFieldAction
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
            'wrap_classes' => $rules_160,
            'type' => $rules_160,
            'general_type' => $rules_160,
            'value' => $rules_160,
            'options' => $default_rules->maxLength(1000)->build(),
            'help_text' => $default_rules->maxLength(320)->build(),
        ];
    }

    /**
     * @return array
     * @throws \Canvass\Exception\InvalidValidationData
     */
    public function getValidationRules()
    {
        $rules = [];

        foreach ($this->getDataColumnsMatchedWithRequiredBoolean() as $rule_name => $is_required) {
            $rules[$rule_name] = [
                'rules' => $this->getRule($rule_name, $is_required)
            ];
        }

        return $rules;
    }

    abstract public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    );

    /**
     * Returns an array of the columns that the field uses.
     *
     * The array's keys are the columns used and the array values are whether the column is required (true) or optional (false).
     *
     * @return array
     */
    abstract public function getDataColumnsMatchedWithRequiredBoolean();

    /**
     * @param $attributes
     * @return bool
     */
    public function validateAttributes($attributes)
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

    /**
     * @return bool
     */
    public function hasValidatableAttributes()
    {
        return ! empty($this->attributes_validation_rules);
    }

    /**
     * @param $attributes
     * @return array
     */
    public function convertAttributesData($attributes)
    {
        return [];
    }

    /**
     * @param $name
     * @param bool $required
     * @return array
     * @throws \Canvass\Exception\InvalidValidationData
     */
    protected function getRule($name, $required)
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

        return $rules;
    }
}
