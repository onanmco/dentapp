<?php

namespace core;

use config\Config;
use Exception;
use ErrorException;

class Error
{
    /**
     * Error handler that converts the errors to Exceptions by throwing ErrorException
     * 
     * @param int $level
     * @param string $message
     * @param string $file
     * @param int $line
     * 
     * @return void 
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Exception handler
     * 
     * @param Exception $exception
     * 
     * @return void
     */
    public static function exceptionHandler($exception)
    {
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        http_response_code($code);   
        try {
            if (Config::SHOW_ERRORS) {
                echo "<h1>Fatal Error</h1>";
                echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
                echo "<p>Message: '" . $exception->getMessage() . "'</p>";
                echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
                echo "<p>Thrown in: '" . $exception->getFile() . "' on line: '" . $exception->getLine() . "'</p>";
            } else {
                $message = self::getMessage($exception);
                self::writeLog($message);
                View::render($code . '.html');
            }
        } catch (\Throwable $t) {
            $message1 = $t->getMessage();
            self::writeLog($message1);
            $message2 = self::getMessage($exception);
            self::writeLog($message2);
            View::render($code . '.html');
        }
    }

    /**
     * Get a formatted error message from the passed exception.
     * 
     * @param Exception $exception
     * 
     * @return string $message
     */
    public static function getMessage($exception)
    {
        $message = "\nUncaught exception: '" . get_class($exception) . "'\n";
        $message .= "Message: '" . $exception->getMessage() . "'\n";
        $message .= "Stack trace: " . $exception->getTraceAsString() . "\n";
        $message .= "Thrown in: '" . $exception->getFile() . "' on line: '" . $exception->getLine() . "'\n";
        return $message;
    }

    /**
     * Write to log the passed error message.
     * 
     * @param string $message
     * 
     * @return void
     */
    public static function writeLog($message)
    {
        $log_file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . date('Y-m-d') . '.txt';
        ini_set('error_log', $log_file);
        error_log($message);
    }

}