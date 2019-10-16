<?php

namespace Canvass\Action\CommonField;

use Canvass\Contract\Action;
use Canvass\Contract\FieldAction;
use Canvass\Contract\FormFieldModel;
use Canvass\Contract\FormModel;

abstract class AbstractFieldAction implements Action, FieldAction
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

    public function getFormId()
    {
        return $this->form->getId();
    }
}
