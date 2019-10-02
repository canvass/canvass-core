<?php

namespace Implement;

use Canvass\Contract\Action;

class Response implements \Canvass\Contract\Response
{
    /** @var string */
    private $type;
    /** @var string */
    private $message;
    /** @var array|null */
    private $data;
    /** @var \Canvass\Contract\Action */
    private $action;

    public function __construct(string $type = 'response', string $message = '')
    {
        $this->type = $type;
        $this->message = $message;
    }

    public function respond(Action $action, $data = null)
    {
        $this->action = $action;

        $this->data = $data;

        return $this;
    }

    public function getAction(): Action
    {
        return $this->action;
    }

    public function getData()
    {
        return $this->data;
    }

    public function __toString()
    {
        $response = $this->type;

        if (! empty($this->message)) {
            $response .= "'{$this->message}'";
        }

        return "{$response} from " . get_class($this->action);
    }
}
