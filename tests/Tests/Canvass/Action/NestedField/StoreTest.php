<?php

namespace Tests\Canvass\Action\NestedField;

use Canvass\Action\NestedField\Store;
use Implement\Model\Form;
use Implement\Model\FormField;
use Tests\TestCase;

class StoreTest extends TestCase
{
    public function test_invoke_creates_nested_field()
    {
        $form = new Form();
        $form->save();

        $field = new FormField(['form_id' => 1]);
        $field->save();

        $create = new Store();

        $create->__invoke(
            $form->getId(),
            $field->getId(),
            0,
            'text',
            [
                'identifier' => 'child-field',
                'classes' => 'form-control',
                'name' => 'child-field',
                'label' => 'Child Field',
            ]
        );

        $child = (new FormField())->find($field->getId() + 1);

        $this->assertEquals($field->getId(), $child->getData('parent_id'));

        $this->assertEquals('Child Field', $child->getData('label'));

        $this->assertEquals('text', $child->getData('type'));

        $this->assertEquals('input', $child->getData('canvass_type'));

        $this->assertEquals(1, $child->getData('sort'));
    }
}
