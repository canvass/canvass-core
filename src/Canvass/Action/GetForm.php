<?php

namespace Canvass\Action;

use Canvass\Contract\FormModel;

final class GetForm
{
    public function run(FormModel $form, $id)
    {
        return ['form' => $form->find($id)];
    }
}
