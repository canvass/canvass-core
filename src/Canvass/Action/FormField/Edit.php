<?php

namespace Canvass\Action\FormField;

use Canvass\Contract\Action\Action;
use Canvass\Contract\Action\FieldAction;
use Canvass\Forge;

final class Edit implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    public function __invoke(int $form_id, int $field_id)
    {
        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        $field = $this->form->findField($field_id);
        /** @var \Canvass\Contract\FormFieldModel[] $children */
        $children = $field->retrieveChildren();

        return Forge::response()->respond(
            $this,
            [
                'form' => $this->form,
                'field' => $field,
                'children' => $children,
                'type' => $field->getData('type'),
                'sort' => $field->getData('sort'),
                'show_type_field' => false,
            ]
        );
    }

    public function getFormId()
    {
        return $this->form->getId();
    }
}
