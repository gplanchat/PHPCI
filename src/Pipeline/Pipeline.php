<?php

namespace Kiboko\Component\Pipeline;

use InvalidArgumentException;
use Kiboko\Component\Pipeline\ExecutionContext\ExecutionContextInterface;
use Kiboko\Component\Pipeline\Processor\ProcessorInterface;

class Pipeline implements ForkablePipelineInterface
{
    /**
     * @var StepInterface[]
     */
    private $steps = [];

    /**
     * @var ForkGroupInterface
     */
    private $group;

    /**
     * Constructor.
     *
     * @param StepInterface[] $steps
     * @param ForkGroupInterface $group
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        array $steps = [],
        ?ForkGroupInterface $group = null
    ) {
        foreach ($steps as $step) {
            if (!$step instanceof StepInterface) {
                throw new InvalidArgumentException(sprintf('All steps should implement %s.', StepInterface::class));
            }
        }

        $this->steps = $steps;
        $this->group = $group;
    }

    /**
     * @inheritdoc
     */
    public function pipe(StepInterface $step): PipelineInterface
    {
        $pipeline = clone $this;
        $pipeline->steps[] = $step;

        return $pipeline;
    }

    /**
     * @param ExecutionContextInterface $executionContext
     * @param ProcessorInterface $processor
     *
     * @return PipelineExecutionInterface
     */
    public function __invoke(
        ExecutionContextInterface $executionContext,
        ProcessorInterface $processor
    ): PipelineExecutionInterface {
        return $processor->process($this->steps, $executionContext);
    }

    /**
     * @return PipelineBuilderInterface
     */
    public function forkBuilder(): PipelineBuilderInterface
    {
        return new PipelineBuilder($this);
    }

    /**
     * @return ForkGroupInterface
     */
    public function getGroup(): ForkGroupInterface
    {
        return $this->group;
    }
}
