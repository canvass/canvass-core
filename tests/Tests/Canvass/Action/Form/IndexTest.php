<?php

namespace Tests\Canvass\Action\Form;

use Canvass\Action\Form\Index;
use Canvass\Forge;
use Implement\Model\Form;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public function test_invoke_shows_forms()
    {
        $form = new Form();
        $form->save();

        $form2 = new Form();
        $form2->save();

        $list = new Index();

        /** @var \Implement\Response $response */
        $response = $list->__invoke();

        $this->assertEquals(
            'response from ' . get_class($list),
            $response->__toString()
        );

        $this->assertCount(2, $response->getData()['forms']);
    }

    public function test_invoke_shows_owned_form()
    {
        $owner_id = Forge::getOwnerId();
        Forge::setOwnerId(1);

        $form = new Form(['owner_id' => 1]);
        $form->save();

        $form2 = new Form(['owner_id' => 9]);
        $form2->save();

        $list = new Index();

        /** @var \Implement\Response $response */
        $response = $list->__invoke();

        Forge::setOwnerId($owner_id);

        $this->assertEquals(
            'response from ' . get_class($list),
            $response->__toString()
        );

        $this->assertCount(1, $response->getData()['forms']);
    }
}
