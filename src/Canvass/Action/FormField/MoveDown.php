<?php

namespace Canvass\Action\FormField;

use Canvass\Action\CommonField\MoveField;
use Canvass\Contract\Action;
use Canvass\Contract\FieldAction;
use Canvass\Exception\InvalidSortException;
use Canvass\Forge;

final class MoveDown implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    public function __invoke(int $form_id, int $field_id)
    {
        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        $field = $this->form->findField($field_id);

        $move = new MoveField($this->form, $field, Forge::getOwnerId());

        return $move->run(MoveField::DOWN);
    }

    public function getFormId()
    {
        return $this->form->getId();
    }
}
