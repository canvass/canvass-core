<?php

namespace Canvass\Contract;


interface Response
{
    public function respond(Action $action, $data = null);
}
