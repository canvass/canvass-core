<?php

namespace Tests\Canvass\Action\NestedField;

use Canvass\Action\NestedField\Edit;
use Canvass\Forge;
use Implement\Model\Form;
use Implement\Model\FormField;
use Implement\Model\ModelNotFoundException;
use Tests\TestCase;

class EditTest extends TestCase
{
    public function test_invoke_returns_edit_response()
    {
        $form = new Form();
        $form->save();

        $field = new FormField();
        $field->save();

        $nested = new FormField(
            ['form_id' => $form->getId(), 'parent_id' => $field->getId()]
        );
        $nested->save();

        $edit = new Edit();

        /** @var \Implement\Response $response */
        $response = $edit->__invoke($form->getId(), $field->getId(), $nested->getId());

        $this->assertEquals(
            'response from ' . get_class($edit),
            $response->__toString()
        );

        $this->assertEquals(
            $form->toArray(),
            $response->getData()['form']->toArray()
        );

        $this->assertEquals(
            $nested->toArray(),
            $response->getData()['field']->toArray()
        );

        $this->assertEquals(
            $field->toArray(),
            $response->getData()['parent']->toArray()
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

        $nested = new FormField(
            ['form_id' => $form->getId(), 'parent_id' => $field->getId()]
        );
        $nested->save();

        $edit = new Edit();

        try {
            $edit->__invoke($form->getId(), $field->getId(), $nested->getId());

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
