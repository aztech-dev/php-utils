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
     * @var float
     */
    private $startTime = 0;

    /**
     *
     * @var float
     */
    private $endTime = 0;

    public function start()
    {
        $this->started = true;
        $this->reset();
    }

    public function stop()
    {
        $this->endTime = (float) microtime(true);
        $this->started = false;
    }

    public function reset()
    {
        $this->startTime = (float) microtime(true);
    }

    public function getElapsed()
    {
        return round($this->endTime - $this->startTime, 3);
    }
}
