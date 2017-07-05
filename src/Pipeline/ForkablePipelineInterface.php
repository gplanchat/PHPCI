<?php

namespace Kiboko\Component\Pipeline;

interface ForkablePipelineInterface extends PipelineInterface
{
    /**
     * @return PipelineBuilderInterface
     */
    public function forkBuilder(): PipelineBuilderInterface;

    /**
     * @return ForkGroupInterface
     */
    public function getGroup(): ForkGroupInterface;
}
