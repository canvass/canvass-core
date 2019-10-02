<?php

namespace Tests\Canvass\Action\FormField;

use Canvass\Action\FormField\Destroy;
use Canvass\Forge;
use Implement\Model\Form;
use Implement\Model\FormField;
use Implement\Model\ModelNotFoundException;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    public function test_invoke_deletes_field()
    {
        $form = new Form();
        $form->save();

        $field = new FormField(['form_id' => $form->getId()]);
        $field->save();

        $field2 = new FormField([
            'form_id' => $form->getId(),
            'label' => 'Field 2',
            'identifier' => 'field-2',
            'name' => 'field-2',
            'sort' => 2,
        ]);
        $field2->save();

        $this->assertEquals(2, $field2->getData('sort'));

        $destroy = new Destroy();

        $destroy->__invoke($form->getId(), $field->getId());

        try {
            (new FormField())->find($field->getId());

            $this->fail('Was supposed to throw ' . ModelNotFoundException::class);
        } catch (ModelNotFoundException $e) {}

        $second = (new FormField())->find($field2->getId());

        $this->assertEquals(1, $second->getData('sort'));

        $this->assertEquals(2, $second->getId());
    }
}
