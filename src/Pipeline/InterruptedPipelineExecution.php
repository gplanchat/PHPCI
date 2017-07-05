<?php

namespace Kiboko\Component\Pipeline;

use Kiboko\Component\Pipeline\ExecutionContext\ExecutionContextInterface;

class InterruptedPipelineExecution implements PipelineExecutionInterface
{
    /**
     * @var PipelineExecutionInterface
     */
    private $decorated;

    /**
     * @var ExecutionContextInterface
     */
    private $executionContext;

    /**
     * InterruptedPipelineExecution constructor.
     *
     * @param PipelineExecutionInterface $decorated
     * @param ExecutionContextInterface $executionContext
     */
    public function __construct(
        PipelineExecutionInterface $decorated,
        ExecutionContextInterface $executionContext
    ) {
        $this->decorated = $decorated;
        $this->executionContext = $executionContext;
    }

    /**
     * @return ExecutionContextInterface
     */
    public function getExecutionContext(): ExecutionContextInterface
    {
        return $this->executionContext;
    }

    /**
     * @return StepExecutionInterface
     */
    public function current()
    {
        return $this->decorated->current();
    }

    public function next()
    {
        $this->decorated->next();
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return $this->decorated->key();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->decorated->valid();
    }

    public function rewind()
    {
        $this->decorated->rewind();
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->decorated->count();
    }
}
