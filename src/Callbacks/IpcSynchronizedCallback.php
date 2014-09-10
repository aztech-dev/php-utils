<?php

namespace Aztech\Util\Callbacks;

use Aztech\Util\File\FileLock;

class IpcSynchronizedCallback
{

    private $callback;

    private $hash;

    private $lockDirectory;

    private $file;

    private $fileLock;

    public function __construct($callback, $lockDirectory = '/tmp/')
    {
        if (! $callback || ! is_callable($callback)) {
            throw new \InvalidArgumentException('Parameter is not a valid callback.');
        }

        $this->callback = $callback;
        $this->hash = md5(var_export($this->callback, true));
        $this->lockDirectory = $lockDirectory;
        $this->fileLock = new FileLock(fopen($this->lockDirectory . '/' . $this->hash . '.lock', 'c+'));
    }

    public function __destruct()
    {
        fclose($this->file);
    }

    public function __invoke()
    {
        $result = null;

        if ($this->file) {
            $result = $this->fileLock->invokeEx(array($this,'call'), func_get_args());
        }

        return $result;
    }

    public function call($handle, $args)
    {
        return call_user_func_array($this->callback, $args);
    }
}
