<?php

namespace Canvass\Contract;

interface Model
{
    public function find($id);

    /**
     * @return bool */
    public function save();

    /**
     * @return bool */
    public function delete();

    /**
     * @return mixed
     */
    public function getId();

    /**
     * Get a given attribute on the model.
     *
     * @param  string  $key
     * @return mixed */
    public function getAttribute($key);

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed */
    public function setAttribute($key, $value);
}
