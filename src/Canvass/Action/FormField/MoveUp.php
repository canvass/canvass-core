<?php

namespace Canvass\Action\FormField;

use Canvass\Action\CommonField\MoveField;
use Canvass\Contract\Action\Action;
use Canvass\Contract\Action\FieldAction;
use Canvass\Forge;

final class MoveUp implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    /**
     * @param int $form_id
     * @param int $field_id
     * @return bool|mixed
     * @throws \Canvass\Exception\InvalidSortException
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public function __invoke($form_id, $field_id)
    {
        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        $field = $this->form->findField($field_id);

        $move = new MoveField($this->form, $field, Forge::getOwnerId());

        return $move->run(MoveField::UP);
    }

    public function getFormId()
    {
        return $this->form->getId();
    }
}
