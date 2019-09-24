<?php

namespace Canvass\Action\FormField;

final class ListFields extends AbstractFieldAction
{
    public function run(int $version = 1)
    {
        return [
            'form' => $this->form,
            'fields' => $this->field->findAllByFormId($this->form->getId(), 0)
        ];
    }
}
