<?php

namespace Kiboko\Component\Pipeline;

class ForkGroup implements ForkGroupInterface
{
    /**
     * @var ForkablePipelineInterface
     */
    private $parentPipeline;

    /**
     * @var PipelineInterface
     */
    private $childPipelines;

    /**
     * ForkGroup constructor.
     *
     * @param ForkablePipelineInterface $parentPipeline
     * @param PipelineInterface[] $childPipelines
     */
    public function __construct(
        ForkablePipelineInterface $parentPipeline,
        PipelineInterface ...$childPipelines
    ) {
        $this->parentPipeline = $parentPipeline;
        $this->childPipelines = $childPipelines;
    }

    /**
     * @param PipelineInterface[] ...$pipelines
     *
     * @return ForkGroupInterface
     */
    public function push(PipelineInterface ...$pipelines): ForkGroupInterface
    {
        array_push($this->childPipelines, ...$pipelines);

        return $this;
    }

    /**
     * @return bool
     */
    public function wait(): bool
    {
        // TODO
    }
}
