<?php

namespace Canvass\Contract;

interface FormFieldModel extends Model
{
    public function findAllByFormId($form_id, $version = 1, $owner_id);
}
