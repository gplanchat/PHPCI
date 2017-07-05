<?php

namespace Kiboko\Component\Pipeline;

interface ForkGroupInterface
{
    /**
     * @param ForkablePipelineInterface[] ...$pipelines
     *
     * @return ForkGroupInterface
     */
    public function push(ForkablePipelineInterface ...$pipelines): ForkGroupInterface;

    /**
     * @param ForkablePipelineInterface[] $pipelines
     *
     * @return bool
     */
    public function wait(ForkablePipelineInterface ...$pipelines): bool;
}
