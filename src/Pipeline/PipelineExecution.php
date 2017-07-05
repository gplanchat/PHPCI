<?php

namespace Kiboko\Component\Pipeline;

class PipelineExecution implements PipelineExecutionInterface
{
    /**
     * @var StepInterface[]
     */
    private $steps;

    /**
     * @var StepExecutionInterface[]
     */
    private $stepExecutions;

    /**
     * @var int
     */
    private $index;

    /**
     * PipelineExecution constructor.
     *
     * @param StepInterface[] $steps
     */
    public function __construct(array $steps)
    {
        $this->steps = $steps;
        $this->stepExecutions = [];
        $this->index = 0;
    }

    private function prepareNext()
    {
        if (!isset($this->steps[$this->index])) {
            return;
        }

        $this->stepExecutions[$this->index] = new StepExecution($this, $this->steps[$this->index]);
    }

    /**
     * @return StepExecutionInterface
     */
    public function current()
    {
        return $this->stepExecutions[$this->index];
    }

    public function next()
    {
        $this->index++;
        $this->prepareNext();
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->index < count($this->steps);
    }

    public function rewind()
    {
        if ($this->index > 0) {
            throw new \RuntimeException('Iterator could not be rewinded.');
        }

        $this->prepareNext();
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->steps);
    }
}
