<?php

namespace Aztech\Util\File;

class Files
{

    /**
     * Execute a callback in an exclusive manner by attempting to obtain a lock
     * on the provided file handle
     * @todo Loop until lock is avail, within timeout
     * @param callable $callback
     * @param string $handle
     * @return mixed|false
     */
    public static function invokeEx($callback, $handle)
    {
        if (flock($handle, LOCK_EX)) {
            $args = func_get_args();
            $args = array_splice($args, 1);

            $result = call_user_func_array($callback,  $args);

            flock($handle, LOCK_UN);

            return $result;
        }

        return false;
    }

    public static function readLineEx($handle)
    {
        $callback = function($handle) {
            $content = '';

            if ($line = fgets($handle)) {
                return $line;
            }

            return null;
        };

        return self::invokeEx($callback, $handle);
    }
}
