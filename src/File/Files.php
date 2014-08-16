<?php

namespace Aztech\Util\File;

class Files
{

    /**
     * Execute a callback in an exclusive manner by attempting to obtain a lock
     * on the provided file handle 
     * @param unknown $callback
     * @param unknown $handle
     * @return unknown
     */
    public static function invokeEx($callback, $handle)
    {
        if (flock($handle, LOCK_EX)) {
            $args = array_splice(func_get_args(), 1);
            $result = call_user_func_array($callback,  $args);

            flock($handle, LOCK_UN);
            
            return $result;
        }
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
