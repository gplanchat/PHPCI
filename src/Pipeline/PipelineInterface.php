<?php

namespace Kiboko\Component\Pipeline\Pipeline;

use Kiboko\Component\Pipeline\Pipeline\Processor\ProcessorInterface;

interface PipelineInterface
{
    /**
     * @param ProcessorInterface $processor
     *
     * @return PipelineExecutionInterface
     */
    public function __invoke(ProcessorInterface $processor): PipelineExecutionInterface;

    /**
     * Create a new pipeline with an appended stage.
     *
     * @param StepInterface $step
     *
     * @return PipelineInterface
     */
    public function pipe(StepInterface $step): PipelineInterface;
}
