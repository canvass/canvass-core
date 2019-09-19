<?php

namespace Canvass\Action;

use Canvass\Contract\FormModel;

final class DeleteForm
{
    public function run(FormModel $form)
    {
        // TODO also need to delete form controls too
        return $form->delete();
    }
}
