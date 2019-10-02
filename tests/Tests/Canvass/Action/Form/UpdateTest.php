<?php

namespace Tests\Canvass\Action\Form;

use Canvass\Action\Form\Update;
use Implement\Model\Form;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    public function test_invoke_updates_form()
    {
        $form = new Form();
        $form->save();

        $create = new Update();

        $create->__invoke(
            $form->getId(),
            [
                'name' => 'Updated Form',
            ]
        );

        $form = (new Form())->find($form->getId());

        $this->assertEquals('Updated Form', $form->getData('name'));
    }
}
