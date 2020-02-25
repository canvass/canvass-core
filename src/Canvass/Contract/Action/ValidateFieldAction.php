<?php

namespace Canvass\Contract\Action;

interface ValidateFieldAction
{
    /** @return array */
    public function getValidationRules();
    /** @param $attributes
     * @return bool */
    public function validateAttributes($attributes);
    /** @return bool */
    public function hasValidatableAttributes();
    /** @param $attributes
     * @return array */
    public function convertAttributesData($attributes);
}
