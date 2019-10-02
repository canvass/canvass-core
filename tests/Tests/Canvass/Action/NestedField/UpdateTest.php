<?php

namespace Tests\Canvass\Action\NestedField;

use Canvass\Action\NestedField\Update;
use Implement\Model\Form;
use Implement\Model\FormField;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    public function test_invoke_updates_nested_field()
    {
        $form = new Form();
        $form->save();

        $field = new FormField(['form_id' => $form->getId()]);
        $field->save();

        $nested = new FormField([
            'form_id' => $form->getId(),
            'parent_id' => $field->getId(),
            'label' => 'Nested Field',
        ]);
        $nested->save();

        $create = new Update();

        $create->__invoke(
            $form->getId(),
            $field->getId(),
            $nested->getId(),
            [
                'identifier' => 'child-field',
                'classes' => 'form-control',
                'name' => 'child-field',
                'label' => 'Child Field',
            ]
        );

        $child = (new FormField())->find($nested->getId());

        $this->assertEquals($field->getId(), $child->getData('parent_id'));

        $this->assertEquals('Child Field', $child->getData('label'));
    }
}
