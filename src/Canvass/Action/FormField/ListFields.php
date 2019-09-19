<?php

namespace Canvass\Action\FormField;

use Canvass\Contract\FormFieldModel;
use Canvass\Contract\FormModel;

final class ListFields
{
    /** @var int|mixed */
    private $owner_id;

    public function __construct($owner_id = null)
    {
        $this->owner_id = $owner_id;
    }

    public function run(
        FormModel $form,
        FormFieldModel $fields,
        int $version = 1
    )
    {
        return [
            'form' => $form,
            'fields' => $fields->findAllByFormId(
                $form->getId(), $version, $this->owner_id
            )
        ];
    }
}
