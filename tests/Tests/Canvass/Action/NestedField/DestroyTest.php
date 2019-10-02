<?php

namespace Tests\Canvass\Action\NestedField;

use Canvass\Action\NestedField\Destroy;
use Canvass\Forge;
use Implement\Model\Form;
use Implement\Model\FormField;
use Implement\Model\ModelNotFoundException;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    public function test_invoke_deletes_nested_field()
    {
        $form = new Form();
        $form->save();

        $field = new FormField(['form_id' => $form->getId()]);
        $field->save();

        $nested1 = new FormField([
            'form_id' => $form->getId(),
            'parent_id' => $field->getId(),
            'label' => 'Nested Field 1',
            'identifier' => 'nested-field-1',
            'name' => 'nested-field-1',
        ]);
        $nested1->save();

        $nested2 = new FormField([
            'form_id' => $form->getId(),
            'parent_id' => $field->getId(),
            'label' => 'Nested Field 2',
            'identifier' => 'nested-field-2',
            'name' => 'nested-field-2',
            'sort' => 2,
        ]);
        $nested2->save();

        $this->assertEquals(2, $nested2->getData('sort'));

        $create = new Destroy();

        $create->__invoke(
            $form->getId(),
            $field->getId(),
            $nested1->getId()
        );

        try {
            (new FormField())->find($nested1->getId());

            $this->fail('Was supposed to throw ' . ModelNotFoundException::class);
        } catch (ModelNotFoundException $e) {}

        $child = (new FormField())->find($nested2->getId());

        $this->assertEquals($field->getId(), $child->getData('parent_id'));

        $this->assertEquals(1, $child->getData('sort'));

        $this->assertEquals(3, $child->getId());
    }
}
