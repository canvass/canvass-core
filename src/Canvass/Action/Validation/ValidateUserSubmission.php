<?php

namespace Canvass\Action\Validation;

use Canvass\Contract\Action;
use Canvass\Forge;
use Canvass\Support\FieldTypes;

final class ValidateUserSubmission extends AbstractValidateDataAction
    implements Action
{
    /** @var \Canvass\Contract\FormModel */
    private $form;
    /** @var \Canvass\Contract\FormFieldModel[] */
    private $fields;
    /** @var \Canvass\Action\Validation\FormField\AbstractValidateFieldAction[] */
    private $validators = [];
    /** @var array */
    private $validation = [];

    public function __construct(
        $form_id,
        $owner_id = null,
        $validator = null,
        $validationConverter = null
    )
    {
        if (null === $validator) {
            $validator = Forge::validator();
        }

        if (null === $validationConverter) {
            $validationConverter = Forge::validationMap();
        }

        parent::__construct($validator, $validationConverter);

        $this->form = Forge::form()->find($form_id, $owner_id);

        $this->fields = $this->form->getNestedFields();

        $this->populateValidationRules();
    }

    public function populateValidationRules()
    {
        foreach ($this->fields as $field) {
            $type = $field['type'];
            $general_type = $field['general_type'];

            if (empty($this->validators[$type])) {
                $this->validators[$type] = FieldTypes::getValidateAction(
                    $type,
                    $general_type
                );
            }
            /** @var \Canvass\Action\Validation\FormField\AbstractValidateFieldAction $validator */
            $validator = $this->validators[$type];

            $validator->populateValidationRulesFromFieldData(
                $field,
                $this->validation
            );
        }
    }

    public function getValidationRules(): array
    {
        return $this->validation;
    }
}
