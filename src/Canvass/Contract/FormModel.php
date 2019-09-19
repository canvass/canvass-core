<?php

namespace Canvass\Contract;

interface FormModel extends Model
{
    public function findAllForListing($owner_id = null);
}
