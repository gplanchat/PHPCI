<?php
namespace Kiboko\Component\Pipeline;

use Kiboko\Component\Pipeline\Processor\ProcessorInterface;

interface PipelineBuilderInterface
{
    /**
     * Add an stage.
     *
     * @param StepInterface[] $steps
     *
     * @return $this
     */
    public function add(StepInterface ...$steps): PipelineBuilderInterface;

    /**
     * Build a new Pipeline object
     *
     * @param ProcessorInterface|null $processor
     *
     * @return PipelineInterface
     */
    public function build(ProcessorInterface $processor = null): PipelineInterface;
}
