<?php

namespace WebAnvil\Placeholder;

use WebAnvil\Interfaces\ActionInterface;

class Response implements \WebAnvil\Interfaces\ResponseInterface
{
    /**
     * @param \WebAnvil\Interfaces\ActionInterface $action
     * @param array|null $data
     * @return mixed
     */
    public function respond(ActionInterface $action, $data = null)
    {
        return '';
    }
}
