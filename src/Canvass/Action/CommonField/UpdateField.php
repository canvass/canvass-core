<?php

namespace Canvass\Action\CommonField;

use Canvass\Contract\FormFieldModel;
use Canvass\Contract\FormModel;
use Canvass\Contract\Validate;
use Canvass\Contract\ValidationMap;
use Canvass\Support\FieldTypes;

final class UpdateField extends AbstractFieldAction
{
    /** @var \Canvass\Contract\Validate */
    private $validator;
    /** @var \Canvass\Contract\ValidationMap */
    private $validation_map;

    public function __construct(
        FormModel $form,
        FormFieldModel $field,
        Validate $validator,
        $owner_id = null,
        ValidationMap $validation_map = null
    )
    {
        parent::__construct($form, $field, $owner_id);

        $this->validator = $validator;

        $this->validation_map = $validation_map;
    }

    /**
     * @param $data
     * @param string|null $type The type to use for data validation
     * @param bool $clear_unset_data_keys If a key from the validation list is not in the $data array, should it be set to null or be left alone?
     * @return bool
     * @throws \Canvass\Exception\InvalidValidationData
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public function run(
        $data,
        $type = null,
        $clear_unset_data_keys = false
    )
    {
        if (null === $type) {
            $type = $this->field->getData('type');
        }

        $validate = FieldTypes::getValidateAction($type);

        if (! $validate->validate($data)) {
            return false;
        }

        foreach (array_keys($validate->getDataColumnsMatchedWithRequiredBoolean()) as $key) {
            if (isset($data[$key])) {
                $this->field->setData($key, $data[$key]);
            } elseif ($clear_unset_data_keys) {
                $this->field->setData($key, null);
            }
        }

        return $this->field->save();
    }

    /**
     * @param \Canvass\Action\CommonField\string $type
     * @return \Canvass\Field\AbstractField\AbstractValidateFieldAction
     */
    protected function getValidateAction($type)
    {
        $ucType = ucfirst(strtolower($type));

        $class = "\Canvass\Action\Validation\FormField\Validate{$ucType}Field";

        /** @var \Canvass\Action\Validation\AbstractValidateDataAction $validate */
        return new $class($this->validator, $this->validation_map);
    }
}
