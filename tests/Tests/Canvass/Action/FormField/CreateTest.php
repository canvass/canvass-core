<?php

namespace Tests\Canvass\Action\FormField;

use Canvass\Action\FormField\Create;
use Implement\Model\Form;
use Implement\Model\FormField;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function test_invoke_creates_field()
    {
        $form = new Form();
        $form->save();

        $create = new Create();

        /** @var \Implement\Response $response */
        $response = $create->__invoke($form->getId(), 'text', 0);

        $this->assertEquals(
            'response from ' . get_class($create),
            $response->__toString()
        );
    }
}
