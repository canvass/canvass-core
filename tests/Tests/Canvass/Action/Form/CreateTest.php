<?php

namespace Tests\Canvass\Action\Form;

use Canvass\Action\Form\Create;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function test_invoke_returns_create_response()
    {
        $create = new Create();

        /** @var \Implement\Response $response */
        $response = $create->__invoke();

        $this->assertEquals(
            'response from ' . get_class($create),
            $response->__toString()
        );
    }
}
