<?php

namespace Desyncr\Wtngrm\Beanstalk\Service;

class BeanstalkWorkerService extends \Desyncr\Wtngrm\Service\AbstractService
{
    protected $instance = null;
    protected $job = null;
    protected $functions = array();
    protected $sleep_interval = 10;

    public function __construct($beanstalk, $options)
    {
        $this->setOptions($options);
        $this->instance = $beanstalk;
    }

    public function add($function, $worker, $target = null)
    {
        $this->functions[$function][] = $worker;
    }

    public function dispatch()
    {
        foreach ($this->functions as $function => $workers) {
            foreach ($workers as $worker) {
                $job = $this->instance->watch($function)->ignore('default');

                $worker->execute($job);
            }
            $this->instance->delete($job);
            usleep($this->sleep_interval);
        }
    }
}
