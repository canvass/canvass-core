<?php

namespace Canvass\Action\FormField;

use Canvass\Contract\Action;
use Canvass\Contract\FieldAction;
use Canvass\Forge;
use Canvass\Support\FieldTypes;

final class SelectInputType implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    public function __invoke(int $form_id, int $sort = null)
    {
        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        return Forge::response()->respond(
            $this,
            [
                'form' => $this->form,
                'sort' => $sort,
                'types' => FieldTypes::getInputTypes(),
            ]
        );
    }

    public function getFormId()
    {
        return $this->form->getId();
    }
}
