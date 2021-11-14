<?php


namespace Codeception\Extension;


class RunProcess
{
    protected $output;
    protected $config = ['sleep' => 0];

    protected static $events = [];

    private $processes = [];
    public function __destruct()
    {
        $this->stopProcess();
    }

    public function stopProcess()
    {
        foreach (array_reverse($this->processes) as $process) {

            if (!$process->isRunning()) {
                continue;
            }
            $this->output->debug('[RunProcess] Stopping ' . $process->getCommandLine());
            $process->stop();
        }
        $this->processes = [];
    }
}