<?php

namespace Canvass\Action;

use Canvass\Contract\FormModel;

final class ListForms
{
    /** @var int|mixed */
    private $owner_id;

    public function __construct($owner_id = null)
    {
        $this->owner_id = $owner_id;
    }

    public function run(FormModel $forms)
    {
        return ['forms' => $forms->findAllForListing($this->owner_id)];
    }
}
