<?php

namespace Kiboko\Component\Pipeline;

interface ForkGroupInterface
{
    /**
     * @param PipelineInterface[] ...$pipelines
     *
     * @return ForkGroupInterface
     */
    public function push(PipelineInterface ...$pipelines): ForkGroupInterface;

    /**
     * @return bool
     */
    public function wait(): bool;
}
