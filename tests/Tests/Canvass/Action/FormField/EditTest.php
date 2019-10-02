<?php

namespace Tests\Canvass\Action\FormField;

use Canvass\Action\FormField\Edit;
use Canvass\Forge;
use Implement\Model\Form;
use Implement\Model\FormField;
use Implement\Model\ModelNotFoundException;
use Tests\TestCase;

class EditTest extends TestCase
{
    public function test_invoke_returns_update_response()
    {
        $form = new Form();
        $form->save();

        $field = new FormField();
        $field->save();

        $edit = new Edit();

        /** @var \Implement\Response $response */
        $response = $edit->__invoke($form->getId(), $field->getId());

        $this->assertEquals(
            'response from ' . get_class($edit),
            $response->__toString()
        );

        $this->assertEquals(
            $form->toArray(),
            $response->getData()['form']->toArray()
        );

        $this->assertEquals(
            $field->toArray(),
            $response->getData()['field']->toArray()
        );
    }

    public function test_invoke_throws_exception()
    {
        $owner_id = Forge::getOwnerId();
        Forge::setOwnerId(1);

        $form = new Form(['owner_id' => 9]);
        $form->save();

        $field = new FormField(['form_id' => $form->getId()]);
        $field->save();

        $edit = new Edit();

        try {
            $edit->__invoke($form->getId(), $field->getId());

            $this->fail(
                'Test should have thrown ' . ModelNotFoundException::class
            );
        } catch (ModelNotFoundException $e) {
            $this->assertInstanceOf(
                ModelNotFoundException::class,
                $e
            );
        }

        Forge::setOwnerId($owner_id);
    }
}
