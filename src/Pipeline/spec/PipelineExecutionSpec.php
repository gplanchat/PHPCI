<?php

namespace spec\Kiboko\Component\Pipeline;

use Kiboko\Component\Pipeline\PipelineExecution;
use Kiboko\Component\Pipeline\StepExecutionInterface;
use Kiboko\Component\Pipeline\StepInterface;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Formatter\Presenter\Differ\Differ;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PipelineExecutionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType(PipelineExecution::class);
    }

    function it_iterates_through_steps(
        StepInterface $stepA,
        StepInterface $stepB,
        StepInterface $stepC,
        StepInterface $stepD
    ) {
        $this->beConstructedWith([$stepA, $stepB, $stepC, $stepD]);
        $this->shouldHaveType(PipelineExecution::class);

        $this->shouldYieldStepExecutions(
            new \ArrayIterator([
                $stepA,
                $stepB,
                $stepC,
                $stepD
            ])
        );
    }

    public function getMatchers()
    {
        return [
            'yieldStepExecutions' => function (\Iterator $subject, \Iterator $expectedSteps) {
                switch (count($subject) <=> count($expectedSteps)) {
                    case -1:
                        throw new FailureException(sprintf(
                            'Step executions count exceeds steps count, found respectively %d and %d.',
                            count($subject), count($expectedSteps)
                        ));
                        break;

                    case 1:
                        throw new FailureException(sprintf(
                            'Steps count exceeds step executions count, found respectively %d and %d.',
                            count($subject), count($expectedSteps)
                        ));
                        break;
                }

                $subject->rewind();
                $expectedSteps->rewind();
                while ($subject->valid() && $expectedSteps->valid()) {
                    /** @var StepExecutionInterface $current */
                    $current = $subject->current();
                    if ($current->getStep() !== $expectedSteps->current()->getWrappedObject()) {
                        throw new FailureException(sprintf(
                            'Steps does not match.'
                        ));
                    }

                    $subject->next();
                    $expectedSteps->next();
                }

                return true;
            }
        ];
    }
}
