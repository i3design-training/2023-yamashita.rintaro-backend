<?php

namespace Log;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

class Log
{
    private static $logger;

    public static function getLogger()
    {
      if (!self::$logger) {
          $dateFormat = "Y-m-d H:i:s";
          $output = "[%datetime%]-[%channel%] %level_name%: %message%\n";
          $formatter = new LineFormatter($output, $dateFormat);
          self::$logger = new Logger('app');
          $stream = new StreamHandler(__DIR__ . '/../app.log', Level::Debug);
          $stream->setFormatter($formatter);
          self::$logger->pushHandler($stream);
        }
        return self::$logger;
    }

    public static function info($message, $context = [])
    {
        self::getLogger()->info($message, $context);
    }

    public static function debug($message, $context = [])
    {
        self::getLogger()->debug($message, $context);
    }

    public static function warning($message, $context = [])
    {
        self::getLogger()->warning($message, $context);
    }

    public static function error($message, $context = [])
    {
        self::getLogger()->error($message, $context);
    }
}