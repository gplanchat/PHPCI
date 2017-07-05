<?php

namespace Kiboko\Component\Pipeline\Processor;

use Kiboko\Component\Pipeline\ExecutionContext\ExecutionContextInterface;
use Kiboko\Component\Pipeline\PipelineExecutionInterface;
use Kiboko\Component\Pipeline\StepInterface;

interface ProcessorInterface
{
    /**
     * @param StepInterface[] $steps
     * @param ExecutionContextInterface $executionContext
     *
     * @return PipelineExecutionInterface
     */
    public function process(
        array $steps,
        ExecutionContextInterface $executionContext
    ): PipelineExecutionInterface;
}
