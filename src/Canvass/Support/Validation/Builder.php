<?php

namespace Canvass\Support\Validation;

class Builder
{
    private $rules = [];

    public function allowNull(bool $allow_null = true): self
    {
        $this->rules['allow_null'] = $allow_null;

        return $this;
    }

    public function dataType(string $type): self
    {
        $this->rules['data_type'] = $type;

        return $this;
    }

    public function dateFormat(string $format = 'Y-m-d'): self
    {
        $this->rules['date_format'] = $format;

        return $this;
    }

    public function inGroup(array $values): self
    {
        $this->rules['in_group'] = $values;

        return $this;
    }

    public function isValue($value): self
    {
        $this->rules['one_of'] = [$value];

        return $this;
    }

    public function addValueToInGroup($value): self
    {
        if (empty($this->rules['in_group'])) {
            $this->rules['in_group'] = [];
        }

        $this->rules['in_group'][] = $value;

        return $this;
    }

    public function maxDate(string $date): self
    {
        $this->rules['max_date'] = $date;

        return $this;
    }

    public function minDate(string $date): self
    {
        $this->rules['min_date'] = $date;

        return $this;
    }

    public function maxLength(int $max): self
    {
        $this->rules['max_length'] = $max;

        return $this;
    }

    public function maxTime(string $time): self
    {
        $this->rules['max_time'] = $time;

        return $this;
    }

    public function minTime(string $time): self
    {
        $this->rules['min_time'] = $time;

        return $this;
    }

    public function maxValue(int $value): self
    {
        $this->rules['max'] = $value;

        return $this;
    }

    public function minValue(int $value): self
    {
        $this->rules['min'] = $value;

        return $this;
    }

    public function minLength(int $max): self
    {
        $this->rules['min_length'] = $max;

        return $this;
    }

    public function numeric(bool $numeric = true): self
    {
        $this->rules['numeric'] = $numeric;

        return $this;
    }

    public function oneOf(array $values): self
    {
        $this->rules['one_of'] = $values;

        return $this;
    }

    public function addOneOf($value): self
    {
        if (empty($this->rules['one_of'])) {
            $this->rules['one_of'] = [];
        }

        $this->rules['one_of'][] = $value;

        return $this;
    }

    public function optional(): self
    {
        return $this->required(false);
    }

    public function required(bool $required = true): self
    {
        $this->rules['required'] = $required;

        return $this;
    }

    public function timeFormat(string $format = 'H:i:s'): self
    {
        return $this->dateFormat($format);
    }

    public function isBoolType(): self
    {
        return $this->dataType('bool');
    }

    public function isStringType(): self
    {
        return $this->dataType('string');
    }

    public function setRule(string $key, $value): self
    {
        $this->rules[$key] = $value;

        return $this;
    }

    public function clearRule(string $key): self
    {
        unset($this->rules[$key]);

        return $this;
    }

    public function build(): array
    {
        return $this->rules;
    }

    public static function start(): self
    {
        return new self();
    }
}
