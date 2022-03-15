<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Support;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class Log
{
    protected static $logger;

    /**
     * Forward call.
     *
     * @param array  $args
     */
    public static function __callStatic(string $method, array $args): mixed
    {
        return forward_static_call_array([self::getLogger(), $method], $args);
    }

    /**
     * Forward call.
     *
     * @param array  $args
     */
    public function __call(string $method, array $args): mixed
    {
        return call_user_func_array([self::getLogger(), $method], $args);
    }

    public static function getLogger()
    {
        return self::$logger ? $logger : self::$logger = self::createDefaultLogger();
    }

    /**
     * Set logger.
     */
    public static function setLogger(LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    /**
     * Tests if logger exists.
     */
    public static function hasLogger(): bool
    {
        return self::$logger ? true : false;
    }

    /**
     * Make a default log instance.
     */
    private static function createDefaultLogger(): Logger
    {
        $log = new Logger('ESign');

        if (defined('PHPUNIT_RUNNING') || php_sapi_name() === 'cli') {
            $log->pushHandler(new NullHandler());
        } else {
            $log->pushHandler(new ErrorLogHandler());
        }

        return $log;
    }
}
