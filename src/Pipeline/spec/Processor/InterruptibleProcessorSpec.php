<?php

namespace spec\Kiboko\Component\Pipeline\Processor;

use Kiboko\Component\Pipeline\ExecutionContext\ExecutionContextInterface;
use Kiboko\Component\Pipeline\ExecutionContext\ProcessManager;
use Kiboko\Component\Pipeline\Processor\ExecutionCheckerInterface;
use Kiboko\Component\Pipeline\Processor\InterruptibleProcessor;
use Kiboko\Component\Pipeline\StepInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InterruptibleProcessorSpec extends ObjectBehavior
{
    function it_is_initializable(
        ProcessManager $processManager,
        ExecutionCheckerInterface $checker
    ) {
        $this->beConstructedWith($processManager, $checker);
        $this->shouldHaveType(InterruptibleProcessor::class);
    }

    function it_is_interruptible(
        ProcessManager $processManager,
        ExecutionCheckerInterface $checker,
        StepInterface $stepA,
        StepInterface $stepB,
        StepInterface $stepC,
        StepInterface $stepD,
        ExecutionContextInterface $executionContext
    ) {
        $this->beConstructedWith($processManager, $checker);

        $stepA->__invoke($processManager, $executionContext)->willReturnArgument(1);
        $stepB->__invoke($processManager, $executionContext)->willReturnArgument(1);
        $stepC->__invoke($processManager, $executionContext)->willReturnArgument(1);
        $stepD->__invoke($processManager, $executionContext)->willReturnArgument(1);

        $checker->check($executionContext)->willReturn(true, true, false, true);

        $this->process([$stepA, $stepB, $stepC, $stepD], $executionContext);
    }
}
