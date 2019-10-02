<?php

namespace Tests\Canvass\Action\NestedField;

use Canvass\Action\NestedField\Create;
use Implement\Model\Form;
use Implement\Model\FormField;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function test_invoke_creates_nested_field()
    {
        $form = new Form();
        $form->save();

        $field = new FormField(['form_id' => 1]);
        $field->save();

        $create = new Create();

        /** @var \Implement\Response $response */
        $response = $create->__invoke($form->getId(), $field->getId(), 0, 'text');

        $this->assertEquals(
            'response from ' . get_class($create),
            $response->__toString()
        );
    }
}
