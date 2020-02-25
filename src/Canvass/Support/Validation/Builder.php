<?php

namespace Canvass\Support\Validation;

class Builder
{
    private $rules = [];

    /** @param bool $allow_null
     * @return \Canvass\Support\Validation\Builder */
    public function allowNull($allow_null = true)
    {
        $this->rules['allow_null'] = $allow_null;

        return $this;
    }

    /** @param string $type
     * @return \Canvass\Support\Validation\Builder */
    public function dataType($type)
    {
        $this->rules['data_type'] = $type;

        return $this;
    }

    /** @param string
     * @return \Canvass\Support\Validation\Builder */
    public function dateFormat($format = 'Y-m-d')
    {
        $this->rules['date_format'] = $format;

        return $this;
    }

    /** @param array
     * @return \Canvass\Support\Validation\Builder */
    public function inGroup(array $values)
    {
        $this->rules['in_group'] = $values;

        return $this;
    }

    /** @param mixed
     * @return \Canvass\Support\Validation\Builder */
    public function isValue($value)
    {
        $this->rules['one_of'] = [$value];

        return $this;
    }

    /** @param mixed
     * @return \Canvass\Support\Validation\Builder */
    public function addValueToInGroup($value)
    {
        if (empty($this->rules['in_group'])) {
            $this->rules['in_group'] = [];
        }

        $this->rules['in_group'][] = $value;

        return $this;
    }

    /** @param string
     * @return \Canvass\Support\Validation\Builder */
    public function maxDate($date)
    {
        $this->rules['max_date'] = $date;

        return $this;
    }

    /** @param string
     * @return \Canvass\Support\Validation\Builder */
    public function minDate($date)
    {
        $this->rules['min_date'] = $date;

        return $this;
    }

    /** @param int
     * @return \Canvass\Support\Validation\Builder */
    public function maxLength($max)
    {
        $this->rules['max_length'] = $max;

        return $this;
    }

    /** @param string
     * @return \Canvass\Support\Validation\Builder */
    public function maxTime($time)
    {
        $this->rules['max_time'] = $time;

        return $this;
    }

    /** @param string
     * @return \Canvass\Support\Validation\Builder */
    public function minTime($time)
    {
        $this->rules['min_time'] = $time;

        return $this;
    }

    /** @param int
     * @return \Canvass\Support\Validation\Builder */
    public function maxValue($value)
    {
        $this->rules['max'] = $value;

        return $this;
    }

    /** @param int
     * @return \Canvass\Support\Validation\Builder */
    public function minValue($value)
    {
        $this->rules['min'] = $value;

        return $this;
    }

    /** @param int
     * @return \Canvass\Support\Validation\Builder */
    public function minLength($max)
    {
        $this->rules['min_length'] = $max;

        return $this;
    }

    /** @param bool $numeric
     * @return \Canvass\Support\Validation\Builder */
    public function numeric($numeric = true)
    {
        $this->rules['numeric'] = $numeric;

        return $this;
    }

    /** @param array $values
     * @return \Canvass\Support\Validation\Builder */
    public function oneOf(array $values)
    {
        $this->rules['one_of'] = $values;

        return $this;
    }

    /** @param $value
     * @return \Canvass\Support\Validation\Builder */
    public function addOneOf($value)
    {
        if (empty($this->rules['one_of'])) {
            $this->rules['one_of'] = [];
        }

        $this->rules['one_of'][] = $value;

        return $this;
    }

    /** @return \Canvass\Support\Validation\Builder */
    public function optional()
    {
        return $this->required(false);
    }

    /**
     * @param bool $required
     * @return \Canvass\Support\Validation\Builder
     */
    public function required($required)
    {
        $this->rules['required'] = $required;

        return $this;
    }

    /**
     * @param string $format
     * @return \Canvass\Support\Validation\Builder
     */
    public function timeFormat($format = 'H:i')
    {
        return $this->dateFormat($format);
    }

    /** @return \Canvass\Support\Validation\Builder */
    public function isBoolType()
    {
        return $this->dataType('bool');
    }

    /** @return \Canvass\Support\Validation\Builder */
    public function isStringType()
    {
        return $this->dataType('string');
    }

    /**
     * @param \Canvass\Support\Validation\string $key
     * @param $value
     * @return \Canvass\Support\Validation\Builder
     */
    public function setRule($key, $value)
    {
        $this->rules[$key] = $value;

        return $this;
    }

    /**
     * @param \Canvass\Support\Validation\string $key
     * @return \Canvass\Support\Validation\Builder
     */
    public function clearRule($key)
    {
        unset($this->rules[$key]);

        return $this;
    }

    /** @return array */
    public function build()
    {
        return $this->rules;
    }

    /** @return \Canvass\Support\Validation\Builder */
    public static function start()
    {
        return new self();
    }
}
