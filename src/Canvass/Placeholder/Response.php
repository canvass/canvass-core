<?php

namespace Canvass\Placeholder;

use Canvass\Contract\Action\Action;

class Response implements \Canvass\Contract\Response
{
    /**
     * @param \Canvass\Contract\Action\Action $action
     * @param null $data
     * @return string
     */
    public function respond(Action $action, $data = null)
    {
        return '';
    }
}
