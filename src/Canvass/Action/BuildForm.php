<?php

namespace Canvass\Action;

use Canvass\Contract\FormModel;
use Canvass\Contract\View;

final class BuildForm
{
    /** @var \Canvass\Contract\FormModel */
    private $form;
    /** @var \Canvass\Contract\View */
    private $renderer;
    
    public function __construct(
        FormModel $form,
        View $renderer
    )
    {
        $this->form = $form;

        $this->renderer = $renderer;
    }

    public function run($csrf_token = null, $html_open = '', $html_close = '')
    {
        $fields = $this->form->getNestedFields();

        $data = $this->form->prepareData($fields, $csrf_token);

        //TODO fix so theres no echo-ing
        echo $html_open;
        echo $this->renderer->render($data);
        echo $html_close;
    }
}
