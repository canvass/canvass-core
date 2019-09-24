<?php

namespace Canvass\Contract;

interface FormModel extends Model
{
    public function findAllForListing($owner_id = null);

    /**
     * @return \Canvass\Contract\FormFieldModel[]|null */
    public function findFields();

    public function findFieldWithSortOf(int $sort): ?FormFieldModel;

    /**
     * @return \Canvass\Contract\FormFieldModel[]|null */
    public function getNestedFields();

    /**
     * @param \Canvass\Contract\FormFieldModel[]|null $fields
     * @return array */
    public function prepareData($fields = null): array;
}
