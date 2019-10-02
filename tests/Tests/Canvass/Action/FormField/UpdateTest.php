<?php

namespace Tests\Canvass\Action\FormField;

use Canvass\Action\FormField\Update;
use Implement\Model\Form;
use Implement\Model\FormField;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    public function test_invoke_updates_field()
    {
        $form = new Form();
        $form->save();

        $field = new FormField(['form_id' => $form->getId()]);
        $field->save();

        $create = new Update();

        $create->__invoke(
            $form->getId(),
            $field->getId(),
            [
                'identifier' => 'updated-field',
                'classes' => 'form-control',
                'name' => 'updated-field',
                'label' => 'Updated Field',
            ]
        );

        $updated = (new FormField())->find($field->getId());

        $this->assertEquals(0, $updated->getData('parent_id'));

        $this->assertEquals('Updated Field', $updated->getData('label'));
    }
}
