<?php

namespace Canvass\Action\FormField;

use Canvass\Contract\FormFieldModel;
use Canvass\Contract\FormModel;
use Canvass\Contract\Validate;
use Canvass\Contract\ValidationMap;

final class CreateChildField extends AbstractFieldAction
{
    /** @var \Canvass\Contract\Validate */
    private $validator;
    /** @var \Canvass\Contract\ValidationMap */
    private $validation_map;
    /** @var \Canvass\Contract\FormFieldModel */
    private $parent;

    public function __construct(
        FormModel $form,
        FormFieldModel $field,
        FormFieldModel $parent,
        Validate $validator,
        $owner_id = null,
        ValidationMap $validation_map = null
    ) {
        parent::__construct($form, $field, $owner_id);

        $this->validator = $validator;

        $this->validation_map = $validation_map;

        $this->parent = $parent;
    }

    public function run($data, string $type, int $sort): bool
    {
        $this->field->setAttribute('parent_id', $this->parent->getId());

        $this->field->setAttribute('name', $this->parent->getAttribute('name'));

        $create = new CreateField(
            $this->form,
            $this->field,
            $this->validator,
            $this->owner_id,
            $this->validation_map
        );

        return $create->run($data, $type, $sort);
    }
}
