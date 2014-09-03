<?php

namespace Aztech\Util\Timer;

class Timer
{

    /**
     *
     * @var bool
     */
    private $started = false;

    /**
     *
     * @var double
     */
    private $startTime = 0;

    /**
     *
     * @var double
     */
    private $endTime = 0;

    public function start()
    {
        $this->started = true;
        $this->reset();
    }

    public function stop()
    {
        $this->endTime = microtime(true);
        $this->started = false;
    }

    public function reset()
    {
        $this->startTime = microtime(true);
    }

    public function getElapsed()
    {
        return round($this->endTime - $this->startTime, 3);
    }
}
