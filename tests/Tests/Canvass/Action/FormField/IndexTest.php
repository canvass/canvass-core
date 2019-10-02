<?php

namespace Tests\Canvass\Action\FormField;

use Canvass\Action\FormField\Index;
use Canvass\Forge;
use Implement\Model\Form;
use Implement\Model\FormField;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public function test_invoke_shows_forms()
    {
        $form = new Form();
        $form->save();

        $field1 = new FormField();
        $field1->save();

        $field2 = new FormField([
            'name' => 'Field 2',
            'sort' => 2,
        ]);
        $field2->save();

        $list = new Index();

        /** @var \Implement\Response $response */
        $response = $list->__invoke($form->getId());

        $this->assertEquals(
            'response from ' . get_class($list),
            $response->__toString()
        );

        $this->assertCount(2, $response->getData()['fields']);
    }
}
