<?php

namespace Kiboko\Component\Pipeline;

use Kiboko\Component\Pipeline\Processor\FingersCrossedProcessor;
use Kiboko\Component\Pipeline\Processor\ProcessorInterface;

class PipelineBuilder implements PipelineBuilderInterface
{
    /**
     * @var StepInterface[]
     */
    private $steps = [];

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
        return new Pipeline($this->steps, $processor ?: new FingersCrossedProcessor());
    }
}
