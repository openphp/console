<?php

namespace Openphp\Console\Scheduling;

class CallbackEvent extends Event
{
    /**
     * @var array
     */
    public $parameters;
    /**
     * @var callable
     */
    public $callback;
    /**
     * @var mixed|null
     */
    public $result;

    /**
     * @param \Closure $callback
     * @param array $parameters
     */
    public function __construct(\Closure $callback, array $parameters = [])
    {
        $this->callback   = $callback;
        $this->parameters = $parameters;
        parent::__construct('');
    }


    /**
     * @return false|mixed|void|null
     */
    public function run()
    {
        parent::callBeforeCallbacks();
        $response       = ($this->callback)(...$this->parameters);
        $this->exitCode = $response === false ? 1 : 0;
        return $response;
    }

    /**
     * @return callable|string
     */
    public function getSummaryForDisplay()
    {
        if (is_string($this->description)) {
            return $this->description;
        }
        return is_string($this->callback) ? $this->callback : 'Callback';
    }
}