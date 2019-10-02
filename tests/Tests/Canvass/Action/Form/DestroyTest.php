<?php

namespace Tests\Canvass\Action\Form;

use Canvass\Action\Form\Destroy;
use Implement\Model\Form;
use Implement\Model\ModelNotFoundException;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    public function test_invoke_deletes_form()
    {
        $form = new Form();
        $form->save();

        $destroy = new Destroy();

        $destroy->__invoke($form->getId());

        try {
            (new Form())->find($form->getId());

            $this->fail('Was supposed to throw ' . ModelNotFoundException::class);
        } catch (ModelNotFoundException $e) {
            $this->assertTrue((bool) 'Form was deleted');
        }
    }
}
