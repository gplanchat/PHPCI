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
     * @param ForkablePipelineInterface[] ...$pipelines
     *
     * @return ForkGroupInterface
     */
    public function push(ForkablePipelineInterface ...$pipelines): ForkGroupInterface
    {
        array_push($this->childPipelines, ...$pipelines);

        return $this;
    }

    /**
     * @param ForkablePipelineInterface[] $pipelines
     *
     * @return bool
     */
    public function wait(ForkablePipelineInterface ...$pipelines): bool
    {
        foreach ($pipelines as $pipeline) {
            if ($pipeline->getGroup() !== $this) {
                throw new \RuntimeException('Those pipelines cold not be awaited by this fork group.');
            }
        }
    }
}
