<?php

namespace Kiboko\Component\Pipeline;

use Kiboko\Component\Pipeline\Processor\ProcessorInterface;

class PipelineBuilder implements PipelineBuilderInterface
{
    /**
     * @var ForkGroupInterface
     */
    private $forkGroup;

    /**
     * @var StepInterface[]
     */
    private $steps = [];

    /**
     * PipelineBuilder constructor.
     *
     * @param ForkablePipelineInterface|null $parentPipeline
     */
    public function __construct(?ForkablePipelineInterface $parentPipeline = null)
    {
        $this->forkGroup = new ForkGroup($parentPipeline);
    }

    /**
     * Add an stage.
     *
     * @param StepInterface[] $steps
     *
     * @return PipelineBuilderInterface
     */
    public function add(StepInterface ...$steps): PipelineBuilderInterface
    {
        foreach ($steps as $step) {
            $this->steps[] = $step;
        }

        return $this;
    }

    /**
     * Build a new Pipeline object
     *
     * @param  ProcessorInterface|null $processor
     *
     * @return PipelineInterface
     */
    public function build(ProcessorInterface $processor = null): PipelineInterface
    {
        return new Pipeline($this->steps, $this->parentPipeline);
    }
}
