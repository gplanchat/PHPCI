<?php

namespace Kiboko\Component\Pipeline;

use InvalidArgumentException;
use Kiboko\Component\Pipeline\ExecutionContext\ExecutionContextInterface;
use Kiboko\Component\Pipeline\Processor\ProcessorInterface;

class Pipeline implements PipelineInterface
{
    /**
     * @var StepInterface[]
     */
    private $steps = [];

    /**
     * Constructor.
     *
     * @param StageInterface[] $steps
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $steps = [])
    {
        foreach ($steps as $step) {
            if (!$step instanceof StepInterface) {
                throw new InvalidArgumentException(sprintf('All steps should implement %s.', StepInterface::class));
            }
        }

        $this->steps = $steps;
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
}
