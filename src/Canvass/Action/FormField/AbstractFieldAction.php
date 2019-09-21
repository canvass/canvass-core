<?php

namespace Canvass\Action\FormField;

use Canvass\Contract\FormFieldModel;
use Canvass\Contract\FormModel;

abstract class AbstractFieldAction
{
    /** @var \Canvass\Contract\FormModel */
    protected $form;
    /** @var \Canvass\Contract\FormFieldModel */
    protected $field;
    /** @var int|mixed */
    protected $owner_id;

    public function __construct(
        FormModel $form,
        FormFieldModel $field,
        $owner_id = null
    )
    {
        $this->form = $form;
        $this->field = $field;
        $this->owner_id = $owner_id;
    }
}
