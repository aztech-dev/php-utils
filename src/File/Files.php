<?php

namespace Aztech\Util\File;

class Files
{

    /**
     * Execute a callback in an exclusive manner by attempting to obtain a lock
     * on the provided file handle
     *
     * @todo Loop until lock is avail, within timeout
     * @param callable $callback
     * @param resource $handle
     * @return mixed|false
     */
    public static function invokeEx($callback, $handle)
    {
        if (! is_resource($handle)) {
            throw new \InvalidArgumentException('Handle must be a file resource.');
        }

        if (flock($handle, LOCK_EX)) {
            $args = func_get_args();
            $args = array_splice($args, 1);

            $result = call_user_func_array($callback,  $args);

            flock($handle, LOCK_UN);

            return $result;
        }

        return false;
    }

    /**
     * Performs a exclusive line read on a file handle
     * @param resource $handle
     * @return string|false
     */
    public static function readLineEx($handle)
    {
        $callback = function($handle) {
            if ($line = fgets($handle)) {
                return $line;
            }

            return false;
        };

        return self::invokeEx($callback, $handle);
    }
}
