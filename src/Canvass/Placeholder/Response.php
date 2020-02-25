<?php

namespace Canvass\Placeholder;

class Response implements \Canvass\Contract\Response
{
    /**
     * @param \Canvass\Contract\Action\Action $action
     * @param null $data
     * @return string
     */
    public function respond($action, $data = null)
    {
        return '';
    }
}
