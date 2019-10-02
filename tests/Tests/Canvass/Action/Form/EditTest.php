<?php

namespace Tests\Canvass\Action\Form;

use Canvass\Action\Form\Edit;
use Canvass\Forge;
use Implement\Model\Form;
use Implement\Model\ModelNotFoundException;
use Tests\TestCase;

class EditTest extends TestCase
{
    public function test_invoke_returns_update_response()
    {
        $form = new Form();
        $form->save();

        $edit = new Edit();

        /** @var \Implement\Response $response */
        $response = $edit->__invoke($form->getId());

        $this->assertEquals(
            'response from ' . get_class($edit),
            $response->__toString()
        );

        $this->assertEquals(
            $form->toArray(),
            $response->getData()['form']->toArray()
        );
    }

    public function test_invoke_throws_exception()
    {
        $owner_id = Forge::getOwnerId();
        Forge::setOwnerId(1);

        $form = new Form(['owner_id' => 9]);
        $form->save();

        $edit = new Edit();

        try {
            $edit->__invoke($form->getId());

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
