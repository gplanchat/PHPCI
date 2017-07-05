<?php

namespace Kiboko\Component\Pipeline;

use Kiboko\Component\Pipeline\ExecutionContext\ExecutionContextInterface;
use Kiboko\Component\Pipeline\ExecutionContext\ProcessManager;

class StepExecution implements StepExecutionInterface
{
    /**
     * @var PipelineExecutionInterface
     */
    private $pipelineExecution;

    /**
     * @var StepInterface
     */
    private $step;

    /**
     * StepExecution constructor.
     * @param PipelineExecutionInterface $pipelineExecution
     * @param StepInterface $step
     */
    public function __construct(
        PipelineExecutionInterface $pipelineExecution,
        StepInterface $step
    ) {
        $this->pipelineExecution = $pipelineExecution;
        $this->step = $step;
    }

    /**
     * @param ProcessManager $processManager
     * @param ExecutionContextInterface $executionContext
     *
     * @return ExecutionContextInterface
     */
    public function execute(
        ProcessManager $processManager,
        ExecutionContextInterface $executionContext
    ): ExecutionContextInterface {
        $step = $this->step;
        return $step($processManager, $executionContext);
    }

    /**
     * @return PipelineExecutionInterface
     */
    public function getPipelineExecution(): PipelineExecutionInterface
    {
        return $this->pipelineExecution;
    }

    /**
     * @return StepInterface
     */
    public function getStep(): StepInterface
    {
        return $this->step;
    }
}
