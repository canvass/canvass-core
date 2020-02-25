<?php

namespace Canvass\Support;

use Canvass\Contract\Validate;
use Canvass\Contract\ValidationMap;
use Canvass\Exception\InvalidValidationData;
use Canvass\Forge;

final class FieldTypes
{
    /** @var string[] Field types that are used in the input element */
    public static $INPUT_TYPES = [
        'checkbox', 'date', 'email', 'number', 'radio',
        'tel', 'text', 'time', 'url',
    ];

    /** @var string[] A map of type (key) to general_type (value) */
    private static $GENERAL_TYPES_MAP = [
        'date' => 'input',
        'email' => 'input',
        'number' => 'input',
        'tel' => 'input',
        'text' => 'input',
        'time' => 'input',
        'url' => 'input',
        'textarea' => 'textarea',
        'select' => 'select',
        'option' => 'option',
        'checkbox' => 'checkbox',
        'radio' => 'radio',
        'group' => 'group',
        'checkbox-group' => 'group',
        'radio-group' => 'group',
        'fieldset' => 'fieldset',
        'divider' => 'divider',
        'columns' => 'columns',
        'column' => 'column',
    ];

    /**
     * Get a list of basic field types
     * @param bool $just_keys
     * @return array
     */
    public static function get($just_keys = false)
    {
        $values = [
            'input' => 'Input',
            'textarea' => 'Textarea',
            'select' => 'Dropdown/Select',
            'checkbox' => 'Checkbox',
            'group' => 'Radio/Checkbox Group',
            'fieldset' => 'Fieldset',
            'divider' => 'Divider',
            'columns' => 'Columns',
            'column' => 'Column'
        ];

        if ($just_keys) {
            return array_keys($values);
        }

        return $values;
    }

    /**
     * Checks to see if the supplied type is a valid/expected value
     *
     * @param string $type
     * @return bool
     * @throws \Canvass\Exception\InvalidValidationData
     */
    public static function isValid($type)
    {
        if (! in_array($type, self::get(true), true)) {
            return ! empty(self::getGeneralTypeFromType($type));
        }

        return true;
    }

    /**
     * Returns the general/generic type string using the supplied value
     *
     * @param string $type
     * @return string
     * @throws \Canvass\Exception\InvalidValidationData
     */
    public static function getGeneralTypeFromType($type)
    {
        if (empty(self::$GENERAL_TYPES_MAP[$type])) {
            $class = self::getClassName($type, 'field type');

            /** @var \Canvass\Contract\FieldType $field_type */
            $field_type = new $class();

            return $field_type->getGeneralType();
        }

        return self::$GENERAL_TYPES_MAP[$type];
    }

    /**
     * Returns the list of input types with a user-friendly title
     *
     * @return string[]
     */
    public static function getInputTypes()
    {
        return [
            'text' => 'Generic Text',
            'tel' => 'Phone Number',
            'email' => 'Email Address',
            'number' => 'Number',
            'date' => 'Date',
            'time' => 'Time',
            'url' => 'Url',
        ];
    }

    /**
     * Returns an instantiated Validate action based on the supplied type(s)
     *
     * @param string $type The field type name
     * @param string|null $alternate_type The general type name
     * @param \Canvass\Contract\Validate|null $validator
     * @param \Canvass\Contract\ValidationMap|null $validation_map
     * @return \Canvass\Field\AbstractField\AbstractValidateFieldAction
     * @throws \Canvass\Exception\InvalidValidationData
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function getValidateAction(
        $type,
        $alternate_type = null,
        Validate $validator = null,
        ValidationMap $validation_map = null
    )
    {
        try {
            $class = self::getClassName($type, 'Validate');
        } catch (InvalidValidationData $e) {
            $class = self::getClassName($alternate_type, 'Validate');
        }

        if (null === $validator) {
            $validator = Forge::validator();
        }

        if (null === $validation_map) {
            $validation_map = Forge::validationMap();
        }

        /**
         * @var \Canvass\Action\Validation\AbstractValidateDataAction $validate
         */
        return new $class($validator, $validation_map);
    }

    /**
     * Returns class path for the desired class (suffix) using the supplied type
     *
     * @param string $type
     * @param string $suffix
     * @return string
     * @throws \Canvass\Exception\InvalidValidationData
     */
    public static function getClassName($type, $suffix)
    {
        $paths = array_reverse(Forge::getFieldPaths());

        $ucType = Str::classSegment($type);

        $ucSuffix = Str::classSegment($suffix);

        foreach ($paths as $path_set) {
            $class = "{$path_set['namespace']}\\{$ucType}\\{$ucSuffix}";

            if (class_exists($class)) {
                return $class;
            }
        }

        throw new InvalidValidationData(
            "There is no {$suffix} for {$type}"
        );
    }
}
