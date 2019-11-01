<?php

namespace Canvass\Contract;

use Canvass\Contract\Action\Action;

interface Response
{
    public function respond(Action $action, $data = null);
}
