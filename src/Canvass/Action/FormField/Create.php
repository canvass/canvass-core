<?php

namespace Canvass\Action\FormField;

use Canvass\Contract\Action\Action;
use Canvass\Contract\Action\FieldAction;
use Canvass\Forge;

final class Create implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    public function __invoke(int $form_id, string $type, int $sort = null)
    {
        \Canvass\Support\FieldTypes::isValid($type);

        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        return Forge::response()->respond(
            $this,
            [
                'form' => $this->form,
                'field' => Forge::field(),
                'type' => $type,
                'sort' => $sort,
            ]
        );
    }

    public function getFormId()
    {
        return $this->form->getId();
    }
}
