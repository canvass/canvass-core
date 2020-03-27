<?php

namespace Canvass\Field\AbstractField\Toggle;

use Canvass\Contract\FieldData;
use Canvass\Support\Validation\Builder;

abstract class ChildFieldValidation
    extends \Canvass\Field\AbstractField\AbstractValidateFieldAction
{
    protected $attributes_validation_rules = [
        'checked' => ['checked' => false,],
    ];

    /**
     * @param \Canvass\Contract\FieldData $field
     * @param array $rules
     */
    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    )
    {
        $rules[$field['name']] = Builder::start()
            ->optional()
            ->isValue($field['value'])
            ->build();
    }

    /** @return array */
    public function getDataColumnsMatchedWithRequiredBoolean()
    {
        return [
            'name' => true,
            'label' => true,
            'identifier' => true,
            'value' => true,
            'classes' => false,
            'wrap_classes' => false,
        ];
    }

    /**
     * @param array|mixed $attributes
     * @return array
     */
    public function convertAttributesData($attributes)
    {
        $return = [];

        if (! empty($attributes['checked'])) {
            $return['checked'] = 'checked';
        }

        return $return;
    }
}
