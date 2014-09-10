<?php

namespace Aztech\Util\File;

class FileLock
{

    private $isHandleOwner = false;

    private $handle;

    public function __construct($file)
    {
        if (is_string($file)) {
            $file = fopen($file, 'c+');
            $this->isHandleOwner = true;
        }

        if (! is_resource($file)) {
            throw new \InvalidArgumentException('File must be a file handle or path.');
        }

        $this->handle = $file;
    }

    public function __destruct()
    {
        if ($this->isHandleOwner && $this->handle !== null) {
            fclose($this->handle);
        }
    }

    /**
     * Execute a callback in an exclusive manner by attempting to obtain a lock
     * on the file.
     *
     * @todo Loop until lock is avail, within timeout
     * @param callable $callback
     * @param mixed $args,...
     * @return mixed|false
     */
    public function invokeEx(callable $callback)
    {
        if (flock($this->handle, LOCK_EX)) {
            $args = func_get_args();
            $args = array_splice($args, 1);

            $result = call_user_func_array($callback,  $args);

            flock($this->handle, LOCK_UN);

            return $result;
        }

        return false;
    }

    /**
     * Performs a exclusive line read.
     * @return string|false
     */
    public function readLineEx()
    {
        $callback = function($handle) {
            if ($line = fgets($handle)) {
                return $line;
            }

            return false;
        };

        return $this->invokeEx($callback, $this->handle);
    }
}
