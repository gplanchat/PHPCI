<?php

namespace Kiboko\Component\Pipeline;

use Kiboko\Component\Pipeline\ExecutionContext\ExecutionContextInterface;
use Kiboko\Component\Pipeline\Processor\ProcessorInterface;

interface PipelineInterface
{
    /**
     * @param ExecutionContextInterface $executionContext
     * @param ProcessorInterface $processor
     *
     * @return PipelineExecutionInterface
     */
    public function __invoke(
        ExecutionContextInterface $executionContext,
        ProcessorInterface $processor
    ): PipelineExecutionInterface;

    /**
     * Create a new pipeline with an appended stage.
     *
     * @param StepInterface $step
     *
     * @return PipelineInterface
     */
    public function pipe(StepInterface $step): PipelineInterface;
}
