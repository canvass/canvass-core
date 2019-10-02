<?php

namespace Canvass\Contract;


interface NestedFieldAction extends FieldAction
{
    public function getParentFieldId();
}
