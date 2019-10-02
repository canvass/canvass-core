<?php

namespace Canvass\Contract;

interface FormModel extends Model
{
    public function findAllForListing($owner_id = null);

    /**
     * @param $field_id
     * @return \Canvass\Contract\FormFieldModel|null */
    public function findField($field_id);

    /**
     * @param mixed|null $parent_id If null, return all fields
     * @return \Canvass\Contract\FormFieldModel[]|null */
    public function findFields($parent_id = null);

    public function findFieldWithSortOf(int $sort, $parent_id = 0): ?FormFieldModel;

    /**
     * @param int $sort
     * @param int $parent_id
     * @return \Canvass\Contract\FormFieldModel[]|null
     */
    public function findFieldsWithSortGreaterThan(
        int $sort,
        $parent_id = 0
    );

    /**
     * @return \Canvass\Contract\FormFieldModel[]|null */
    public function getNestedFields();

    /**
     * @param \Canvass\Contract\FormFieldModel[]|null $fields
     * @return array */
    public function prepareData($fields = null): array;
}
