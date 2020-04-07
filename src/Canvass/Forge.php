<?php

namespace Canvass;

use Canvass\Contract\FormFieldModel;
use Canvass\Exception\InvalidValidationData;
use Canvass\Placeholder\Form as EmptyForm;
use Canvass\Placeholder\FormField as EmptyField;
use Canvass\Placeholder\Response as EmptyResponse;
use Canvass\Support\FieldTypes;
use WebAnvil\ForgeClosureNotFoundException;
use WebAnvil\Interfaces\ActionInterface;

final class Forge extends \WebAnvil\Forge
{
    const RESPONSE_KEY = 'response';

    private static $owner_id;

    private static $base_url_segment = '/form/';

    private static $field_paths = [];

    /**
     * @return \Canvass\Contract\FormModel
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function form()
    {
        if (null !== ($result = self::handleClosure('form'))) {
            return $result;
        }

        return new EmptyForm();
    }

    /**
     * @return \Canvass\Contract\FormFieldModel
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function field()
    {
        if (null !== ($result = self::handleClosure('field'))) {
            return $result;
        }

        return new EmptyField();
    }

    /**
     * @param \Canvass\Contract\FormFieldModel $field
     * @return \Canvass\Contract\FieldData
     */
    public static function fieldData(FormFieldModel $field)
    {
        $type = $field->getData('type');

        try {
            $class = FieldTypes::getClassName($type, 'FieldData');

            return new $class($field);
        } catch (InvalidValidationData $ignore) {}

        $general_type = $field->getData('general_type');

        try {
            $class = FieldTypes::getClassName($general_type, 'FieldData');

            return new $class($field);
        } catch (InvalidValidationData $ignore) {}

        return new \Canvass\Support\FieldData($field);
    }

    /**
     * @param array|null $fields
     * @return array
     */
    public static function requestData(array $fields = null): array
    {
        try {
            $closure = self::get('request');
        } catch (ForgeClosureNotFoundException $e) {
            return [];
        }

        return $closure($fields);
    }

    /**
     * @return \Canvass\Contract\Response
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function response()
    {
        if (null !== ($result = self::handleClosure(self::RESPONSE_KEY))) {
            return $result;
        }

        return new EmptyResponse();
    }

    /** @return mixed|null */
    public static function getOwnerId()
    {
       return self::$owner_id;
    }

    /** @return string */
    public static function getBaseUrlSegment()
    {
        return self::$base_url_segment;
    }

    /**
     * @param string $message
     * @param ActionInterface $action
     * @return mixed
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function success($message, ActionInterface $action)
    {
        try {
            $success = self::get('success');

            return $success($message, $action);
        } catch (ForgeClosureNotFoundException $ignore) {}

        $response = self::get(self::RESPONSE_KEY);

        return $response();
    }

    /**
     * @param string $message
     * @param ActionInterface $action
     * @return mixed
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function error($message, ActionInterface $action)
    {
        try {
            $error = self::get('error');

            return $error($message, $action);
        } catch (ForgeClosureNotFoundException $ignore) {}

        $response = self::get(self::RESPONSE_KEY);

        return $response();
    }

    /**
     * @param \Exception|\Throwable
     * @return void
     */
    public static function logThrowable($e): void
    {
        try {
            $logger = self::get('log');

            $logger($e);
        } catch (ForgeClosureNotFoundException $ignore) {}
    }

    /**
     * @param \Closure
     * @return void
     */
    public static function setFormClosure(\Closure $form)
    {
        self::set('form', $form);
    }

    /**
     * @param \Closure
     * @return void
     */
    public static function setFieldClosure(\Closure $field)
    {
        self::set('field', $field);
    }

    /**
     * @param \Closure
     * @return void
     */
    public static function setValidatorClosure(\Closure $validate): void
    {
        self::set('validate', $validate);
    }

    /**
     * @param \Closure
     * @return void
     */
    public static function setValidationMapClosure(\Closure $map): void
    {
        self::set('validation_map', $map);
    }

    /**
     * @param \Closure
     * @return void
     */
    public static function setLoggerClosure(\Closure $closure): void
    {
        self::set('log', $closure);
    }

    /**
     * @param \Closure
     * @return void
     */
    public static function setRequestDataClosure(\Closure $request): void
    {
        self::set('request', $request);
    }

    /**
     * @param \Closure
     * @return void
     */
    public static function setResponseClosure(\Closure $response): void
    {
        self::set('response', $response);
    }

    /**
     * @param \Closure
     * @return void
     */
    public static function setSuccessClosure(\Closure $response): void
    {
        self::set('success', $response);
    }

    /**
     * @param \Closure
     * @return void
     */
    public static function setErrorClosure(\Closure $response): void
    {
        self::set('error', $response);
    }

    /**
     * @param int $owner_id
     * @return void
     */
    public static function setOwnerId($owner_id)
    {
        self::$owner_id = $owner_id;
    }

    /**
     * @param string
     * @return void
     */
    public static function setBaseUrlSegment($segment)
    {
        self::$base_url_segment =
            '/' . trim($segment, "/ \t\n\r\0\x0B") . '/';
    }

    /**
     * @param string $path
     * @param string $namespace
     * @return void
     */
    public static function addFieldPath($path, $namespace)
    {
        self::setDefaultPath();

        self::$field_paths[] = ['namespace' => $namespace, 'path' => $path];
    }

    /** @return array */
    public static function getFieldPaths()
    {
        self::setDefaultPath();

        return self::$field_paths;
    }

    private static function setDefaultPath()
    {
        if (empty(self::$field_paths)) {
            self::$field_paths[] = [
                'namespace' => __NAMESPACE__ . '\\Field',
                'path' => __DIR__ . '/Field'
            ];
        }
    }
}
