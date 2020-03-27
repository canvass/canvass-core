<?php

namespace Canvass\Action\Form;

use Canvass\Forge;

class Create implements \Canvass\Contract\Action\Action
{
    public function __invoke()
    {
        return Forge::response()->respond(
            $this,
            ['form' => Forge::form()]
        );
    }
}
