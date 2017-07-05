<?php

namespace Kiboko\Component\Pipeline;

use Kiboko\Component\Pipeline\ExecutionContext\ExecutionContextInterface;
use Kiboko\Component\Pipeline\ExecutionContext\ProcessManager;

interface StepExecutionInterface
{
    /**
     * @param ProcessManager $processManager
     * @param ExecutionContextInterface $executionContext
     *
     * @return ExecutionContextInterface
     */
    public function execute(
        ProcessManager $processManager,
        ExecutionContextInterface $executionContext
    ): ExecutionContextInterface;

    /**
     * @return PipelineExecutionInterface
     */
    public function getPipelineExecution(): PipelineExecutionInterface;

    /**
     * @return StepInterface
     */
    public function getStep(): StepInterface;
}
