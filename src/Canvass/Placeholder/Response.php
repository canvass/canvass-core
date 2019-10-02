<?php

namespace Canvass\Placeholder;

use Canvass\Contract\Action;

class Response implements \Canvass\Contract\Response
{
    public function respond(Action $action, $data = null)
    {
        return '';
    }
}
