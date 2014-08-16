<?php

namespace Aztech\Util\Callbacks;

use Aztech\Events\File\Files;

class IpcSynchronizedCallback
{

    private $callback;

    private $hash;

    private $lockDirectory;

    private $file;

    public function __construct($callback, $lockDirectory = '/tmp/')
    {
        if (! $callback || ! is_callable($callback)) {
            throw new \InvalidArgumentException('Parameter is not a valid callback.');
        }
        
        $this->callback = $callback;
        $this->hash = var_export($this->callback, true);
        $this->lockDirectory = $lockDirectory;
    }

    public function __invoke()
    {
        $this->file = fopen($this->lockDirectory . '/' . $this->hash . '.lock', 'c+');
        
        if ($this->file) {
            $result = Files::invokeEx(array($this,'call'), $handle, func_get_args());
            
            fclose($this->file);
        }
    }

    public function call($handle, $args)
    {
        return call_user_func_array($this->callback, $args);
    }
}
