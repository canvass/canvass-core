<?php

namespace Canvass\Action\Form;

use Canvass\Forge;

class Index implements \Canvass\Contract\Action\Action
{
    public function __invoke()
    {
        return Forge::response()->respond(
            $this, [
                'forms' => Forge::form()
                    ->findAllForListing(Forge::getOwnerId())
            ]
        );
    }
}
