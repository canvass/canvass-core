<?php

namespace Canvass\Field\AbstractField\Input;

use Canvass\Field\AbstractField\AbstractValidateFieldAction;
use Canvass\Contract\FieldData;
use Canvass\Support\Validation\Builder;

abstract class Validate extends AbstractValidateFieldAction
{
    /** @return array */
    public function getDataColumnsMatchedWithRequiredBoolean()
    {
        return [
            'name' => true,
            'label' => true,
            'identifier' => true,
            'classes' => false,
            'wrap_classes' => false,
        ];
    }

    /**
     * @param \Canvass\Contract\FieldData $field
     * @param array $rules
     */
    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    )
    {
        $this->populateTextBasedFieldRules($field, $rules);
    }

    /**
     * @param \Canvass\Contract\FieldData $field
     * @param array $rules
     */
    public function populateTextBasedFieldRules(
        FieldData $field,
        array &$rules
    )
    {
        $builder = Builder::start()
            ->required($field->hasAttribute('required'))
            ->isStringType();

        if ($field->hasAttribute('minlength')) {
            $builder->minLength($field->getAttribute('minlength'));
        }

        if ($field->hasAttribute('maxlength')) {
            $builder->maxLength($field->getAttribute('maxlength'));
        }

        $rules[$field['name']] = [
            'field' => $field,
            'rules' => $builder->build()
        ];
    }
}
