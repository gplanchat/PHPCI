<?php

namespace Kiboko\Component\Pipeline\Processor;

use Kiboko\Component\Pipeline\ExecutionContext\ExecutionContextInterface;
use Kiboko\Component\Pipeline\ExecutionContext\ProcessManager;
use Kiboko\Component\Pipeline\InterruptedPipelineExecution;
use Kiboko\Component\Pipeline\PipelineExecution;
use Kiboko\Component\Pipeline\PipelineExecutionInterface;
use Kiboko\Component\Pipeline\StepExecutionInterface;
use Kiboko\Component\Pipeline\StepInterface;

class InterruptibleProcessor implements ProcessorInterface
{
    /**
     * @var ProcessManager
     */
    private $processManager;

    /**
     * @var ExecutionCheckerInterface
     */
    private $checker;

    /**
     * InterruptibleProcessor constructor.
     *
     * @param ProcessManager $processManager
     * @param ExecutionCheckerInterface $checker
     */
    public function __construct(
        ProcessManager $processManager,
        ?ExecutionCheckerInterface $checker = null
    ) {
        $this->processManager = $processManager;
        $this->checker = $checker;
    }

    /**
     * @param StepInterface[] $steps
     * @param ExecutionContextInterface $executionContext
     *
     * @return PipelineExecutionInterface
     */
    public function process(
        array $steps,
        ExecutionContextInterface $executionContext
    ): PipelineExecutionInterface {
        $pipelineExecution = new PipelineExecution($steps);
        return $this->run($pipelineExecution, $executionContext);
    }

    /**
     * @param PipelineExecutionInterface $pipelineExecution
     * @param ExecutionContextInterface $executionContext
     *
     * @return PipelineExecutionInterface
     */
    public function run(
        PipelineExecutionInterface $pipelineExecution,
        ExecutionContextInterface $executionContext
    ): PipelineExecutionInterface {
        /** @var StepExecutionInterface $stepExecution */
        foreach ($pipelineExecution as $stepExecution) {
            $executionContext = $stepExecution->execute(
                $this->processManager, $executionContext
            );

            if (true !== $this->checker->check($executionContext)) {
                return new InterruptedPipelineExecution($pipelineExecution, $executionContext);
            }
        }

        return $pipelineExecution;
    }

    /**
     * @param InterruptedPipelineExecution $pipelineExecution
     *
     * @return PipelineExecutionInterface
     */
    public function resume(InterruptedPipelineExecution $pipelineExecution): PipelineExecutionInterface
    {
        return $this->run($pipelineExecution, $pipelineExecution->getExecutionContext());
    }
}
