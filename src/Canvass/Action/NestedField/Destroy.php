<?php

namespace Canvass\Action\NestedField;

use Canvass\Action\FormField\DeleteField;
use Canvass\Contract\Action;
use Canvass\Contract\FieldAction;
use Canvass\Contract\NestedFieldAction;
use Canvass\Forge;

final class Destroy implements Action, FieldAction, NestedFieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;
    /** @var \Canvass\Contract\FormFieldModel */
    private $parent;

    public function __invoke(int $form_id, int $parent_id, int $field_id)
    {
        $this->form = \Canvass\Forge::form()->find($form_id, Forge::getOwnerId());

        $this->parent = $this->form->findField($parent_id);

        /** @var \Canvass\Contract\FormFieldModel $field */
        $field = \Canvass\Forge::field()->find($field_id);

        $destroyer = new DeleteField($this->form, $field, null);

        try {
            $destroyed = $destroyer->run();
        } catch (\Throwable $e) {
            Forge::logThrowable($e);

            $destroyed = false;
        }

        if (! $destroyed) {
            return Forge::error(
                'Could not delete parent field for unknown reasons.',
                $this
            );
        }

        return Forge::success(
            sprintf(
                'Field field, %s, has been deleted.',
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
