<?php

namespace Tests\Canvass\Action\FormField;

use Canvass\Action\FormField\Store;
use Implement\Model\Form;
use Implement\Model\FormField;
use Tests\TestCase;

class StoreTest extends TestCase
{
    public function test_invoke_stores_field()
    {
        $form = new Form();
        $form->save();

        $create = new Store();

        $create->__invoke(
            $form->getId(),
            'text',
            0,
            [
                'identifier' => 'field-id',
                'classes' => 'form-control',
                'name' => 'field-id',
                'label' => 'Field ID',
            ]
        );

        $added = (new FormField())->find(1);

        $this->assertEquals(0, $added->getData('parent_id'));

        $this->assertEquals('Field ID', $added->getData('label'));

        $this->assertEquals('text', $added->getData('type'));

        $this->assertEquals('input', $added->getData('general_type'));

        $this->assertEquals(1, $added->getData('sort'));
    }
}
