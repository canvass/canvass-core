<?php

namespace Canvass\Action\Form;

use Canvass\Forge;

class Edit implements \Canvass\Contract\Action
{
    public function __invoke(int $form_id)
    {
        return Forge::response()->respond(
            $this,
            ['form' => Forge::form()->find($form_id, Forge::getOwnerId())]
        );
    }
}
