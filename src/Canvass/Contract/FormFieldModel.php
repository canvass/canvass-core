<?php

namespace Canvass\Contract;

interface FormFieldModel extends Model
{
    public function findAllByFormId($form_id, $parent_id = null);

    public function getHtmlType(): string;

    public function prepareData(): array;

    public function retrieveChildren();
}
