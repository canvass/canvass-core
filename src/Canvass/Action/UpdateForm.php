<?php

namespace Canvass\Action;

use Canvass\Contract\FormModel;

final class UpdateForm
{
    public function run(FormModel $form, $data)
    {
        foreach ($data as $key => $value) {
            $form->setAttribute($key, $value);
        }

        return $form->save();
    }
}
