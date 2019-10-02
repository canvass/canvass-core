<?php

namespace Canvass\Action\NestedField;

use Canvass\Action\FormField\UpdateField;
use Canvass\Contract\Action;
use Canvass\Contract\FieldAction;
use Canvass\Contract\NestedFieldAction;
use Canvass\Forge;

final class Update implements Action, FieldAction, NestedFieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;
    /** @var \Canvass\Contract\FormFieldModel */
    private $parent;

    public function __invoke(
        int $form_id,
        int $parent_id,
        int $field_id,
        $data = null
    )
    {
        if (null === $data) {
            $data = Forge::requestData();
        }

        $this->form = \Canvass\Forge::form()->find($form_id, Forge::getOwnerId());

        $this->parent = $this->form->findField($parent_id);

        $field = $this->form->findField($field_id);

        $update = new UpdateField(
            $this->form,
            $field,
            Forge::validator(),
            Forge::getOwnerId(),
            Forge::validationMap()
        );

        try {
            $updated = $update->run($data, $field->getData('type'));
        } catch (\Throwable $e) {
            Forge::logThrowable($e);

            $updated = false;
        }

        if (! $updated) {
            return Forge::error('Could not update field option.', $this);
        }

        return Forge::success(
            sprintf(
                '%s has been updated.',
                $field->getData('label') ?? $field->getData('identifier')
            ),
            $this
        );
    }

    public function getFormId()
    {
        return $this->form->getId();
    }

    public function getParentFieldId()
    {
        return $this->parent->getId();
    }
}
