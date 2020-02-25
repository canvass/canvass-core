<?php

namespace WebAnvil;

use \Closure;
use WebAnvil\Interfaces\ActionInterface;
use WebAnvil\Interfaces\ValidatorInterface;
use WebAnvil\Placeholder\Response as EmptyResponse;
use WebAnvil\Placeholder\Validator as EmptyValidate;

abstract class Forge
{
    private static $forges = [];

    /**
     * @return ValidatorInterface
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function validator()
    {
        if (null !== ($result = self::handleClosure('validate'))) {
            return $result;
        }

        return new EmptyValidate();
    }

    /**
     * @return \WebAnvil\Interfaces\ValidationMapInterface|null
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function validationMap()
    {
        return self::handleClosure('validation_map', false);
    }

    /**
     * @param array|null $fields
     * @return array
     */
    public static function requestData(array $fields = null)
    {
        try {
            $closure = self::get('request');
        } catch (ForgeClosureNotFoundException $e) {
            return [];
        }

        return $closure($fields);
    }

    /**
     * @return \WebAnvil\Interfaces\ResponseInterface
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function response()
    {
        if (null !== ($result = self::handleClosure('response'))) {
            return $result;
        }

        return new EmptyResponse();
    }

    /**
     * @param string $message
     * @param \WebAnvil\Interfaces\ActionInterface $action
     * @return mixed
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function success($message, ActionInterface $action)
    {
        try {
            $success = self::get('success');

            return $success($message, $action);
        } catch (ForgeClosureNotFoundException $ignore) {}

        $response = self::get('response');

        return $response();
    }

    public static function startTransaction()
    {
        $transaction = self::get('transaction_start');

        return $transaction();
    }

    public static function commitTransaction()
    {
        $transaction = self::get('transaction_commit');

        return $transaction();
    }

    public static function rollbackTransaction()
    {
        $transaction = self::get('transaction_rollback');

        return $transaction();
    }

    /**
     * @param string $message
     * @param \WebAnvil\Interfaces\ActionInterface $action
     * @return mixed
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function error($message, ActionInterface $action)
    {
        try {
            $error = self::get('error');

            return $error($message, $action);
        } catch (ForgeClosureNotFoundException $ignore) {}

        $response = self::get('response');

        return $response();
    }

    /**
     * @param \Exception|\Throwable $e
     * @return void */
    public static function logThrowable($e)
    {
        try {
            $logger = self::get('log');

            $logger($e);
        } catch (ForgeClosureNotFoundException $ignore) {}
    }

    /**
     * @param string $key
     * @param bool $throw_exception
     * @return mixed|null
     * @throws ForgeClosureNotFoundException
     */
    protected static function handleClosure($key, $throw_exception = true)
    {
        $closure = self::get($key, $throw_exception);

        if (null === $closure) {
            return null;
        }

        return $closure();
    }

    /**
     * @param string $key
     * @param bool $throw_exception
     * @return \Closure|null
     * @throws ForgeClosureNotFoundException
     */
    public static function get($key, $throw_exception = true)
    {
        if ($throw_exception && empty(self::$forges[$key])) {
            throw new ForgeClosureNotFoundException(
                "Closure for {$key} not found"
            );
        }

        if (! empty(self::$forges[$key])) {
            return self::$forges[$key];
        }

        return null;
    }

    /**
     * @param $key
     * @param \Closure $closure
     * @return void
     */
    public static function set($key, Closure $closure)
    {
        self::$forges[$key] = $closure;
    }

    /**
     * @param \Closure $validate
     * @return void
     */
    public static function setValidatorClosure(\Closure $validate)
    {
        self::set('validate', $validate);
    }

    /**
     * @param \Closure $map
     * @return void
     */
    public static function setValidationMapClosure(\Closure $map)
    {
        self::set('validation_map', $map);
    }

    /**
     * @param \Closure
     * @return void
     */
    public static function setLoggerClosure(\Closure $closure)
    {
        self::set('log', $closure);
    }

    /**
     * @param \Closure
     * @return void
     */
    public static function setRequestDataClosure(\Closure $request)
    {
        self::set('request', $request);
    }

    /**
     * @param \Closure
     * @return void
     */
    public static function setResponseClosure(\Closure $response)
    {
        self::set('response', $response);
    }

    /**
     * @param \Closure
     * @return void
     */
    public static function setSuccessClosure(\Closure $response)
    {
        self::set('success', $response);
    }

    /**
     * @param \Closure
     * @return void
     */
    public static function setErrorClosure(\Closure $response)
    {
        self::set('error', $response);
    }

    /** @param \Closure */
    public static function setTransactionStartClosure(\Closure $start)
    {
        self::set('transaction_start', $start);
    }

    /** @param \Closure */
    public static function setTransactionCommitClosure(\Closure $commit)
    {
        self::set('transaction_commit', $commit);
    }

    /** @param \Closure */
    public static function setTransactionRollbackClosure(\Closure $rollback)
    {
        self::set('transaction_rollback', $rollback);
    }

    public static function debug()
    {
        echo __FILE__ . ' on line ' . __LINE__;
        echo '<pre style="background: white; width: 1000px;">' . PHP_EOL;
        print_r(self::$forges);
        echo PHP_EOL . '</pre>' . PHP_EOL;
        exit;
    }
}
