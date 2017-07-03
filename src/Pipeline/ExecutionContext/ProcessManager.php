<?php

namespace Kiboko\Component\Pipeline\ExecutionContext;

use Symfony\Component\Process\Process;

class ProcessManager implements \Countable
{
    /**
     * @var int
     */
    private $maxProcesses;

    /**
     * @var int
     */
    private $pollTime;

    /**
     * @var Process[]
     */
    private $pendingProcesses;

    /**
     * @var Process[]
     */
    private $currentProcesses;

    /**
     * ProcessManager constructor.
     *
     * @param int $maxProcesses
     * @param int $pollTime
     */
    public function __construct($maxProcesses = 1, $pollTime = 1000)
    {
        $this->maxProcesses = max($maxProcesses, 1);
        $this->pollTime = $pollTime;
        $this->pendingProcesses = [];
        $this->currentProcesses = [];
    }

    /**
     * @param Process $process
     *
     * @return ProcessManager
     */
    public function enqueue(Process $process): ProcessManager
    {
        $this->pendingProcesses[] = $process;

        return $this;
    }

    public function run(callable $callback): ProcessManager
    {
        do {
            $processesToStart = $this->maxProcesses - count($this->currentProcesses);
            if ($processesToStart > 0) {
                $newProcesses = array_splice($this->pendingProcesses, 0, $processesToStart);
                $this->startProcesses($newProcesses);

                /** @var Process[] $activeProcesses */
                array_push($this->currentProcesses, ...$newProcesses);
            }

            usleep($this->pollTime);

            $stoppedProcesses = [];
            foreach ($this->currentProcesses as $index => $process) {
                if ($process->isRunning()) {
                    continue;
                }

                $stoppedProcesses[] = $process;
                unset($this->currentProcesses[$index]);
            }
        } while ($callback($this, $stoppedProcesses));

        return $this;
    }

    /**
     * @param Process[] $processes
     */
    private function startProcesses(array $processes)
    {
        foreach ($processes as $process) {
            $process->start();
        }
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->pendingProcesses) + count($this->pendingProcesses);
    }
}
