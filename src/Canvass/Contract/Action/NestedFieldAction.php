<?php

namespace Canvass\Contract\Action;


interface NestedFieldAction extends FieldAction
{
    public function getParentFieldId();
}
