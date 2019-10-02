<?php

namespace Tests\Canvass\Action\Form;

use Canvass\Action\Form\Store;
use Implement\Model\Form;
use Tests\TestCase;

class StoreTest extends TestCase
{
    public function test_invoke_creates_form()
    {
        $create = new Store();

        $create->__invoke(
            [
                'name' => 'Form',
                'introduction' => 'Intro, yo!',
                'redirect_url' => '/confirm',
                'identifier' => 'form',
                'classes' => 'form',
                'button_text' => 'Submit',
                'button_classes' => 'btn btn-primary',
            ]
        );

        $forms = (new Form())->findAllForListing();

        $form = current($forms);

        $this->assertCount(1, $forms);

        $this->assertEquals('Form', $form->getData('name'));
    }
}
