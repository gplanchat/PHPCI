<?php

namespace Kiboko\Component\Pipeline;

interface ForkStepInterface extends StepInterface
{
    /**
     * @param ForkablePipelineInterface $parent
     * @param PipelineInterface[] $pipelines
     *
     * @return ForkGroupInterface
     */
    public function fork(ForkablePipelineInterface $parent, PipelineInterface ...$pipelines): ForkGroupInterface;

    /**
     * @param ForkGroupInterface $group
     *
     * @return ForkStepInterface
     */
    public function join(ForkGroupInterface $group): ForkStepInterface;
}
