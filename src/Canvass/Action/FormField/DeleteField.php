<?php

namespace Canvass\Action\FormField;

final class DeleteField extends AbstractFieldAction
{
    public function run()
    {
        // TODO also need to delete form controls too
        return $this->field->delete();
    }
}
