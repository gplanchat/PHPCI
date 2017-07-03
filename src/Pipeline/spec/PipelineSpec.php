<?php

namespace spec\Kiboko\Component\Pipeline;

use Kiboko\Component\Pipeline\ExecutionContext\ExecutionContextInterface;
use Kiboko\Component\Pipeline\PipelineExecutionInterface;
use Kiboko\Component\Pipeline\PipelineInterface;
use Kiboko\Component\Pipeline\Processor\ProcessorInterface;
use Kiboko\Component\Pipeline\StepInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PipelineSpec extends ObjectBehavior
{
    function it_is_initializable(
        StepInterface $step
    ) {
        $this->beConstructedWith([$step]);
        $this->shouldHaveType(PipelineInterface::class);
    }

    function it_can_pipe_steps(
        StepInterface $step
    ) {
        $this->beConstructedWith([]);
        $this->pipe($step)->shouldReturnAnInstanceOf(PipelineInterface::class);
    }

    function it_executes_steps(
        ExecutionContextInterface $executionContext,
        ProcessorInterface $processor,
        StepInterface $step,
        PipelineExecutionInterface $pipelineExecution
    ) {
        $processor->process([$step], $executionContext)->shouldBeCalled()->willReturn($pipelineExecution);

        $this->beConstructedWith([$step]);
        $this->callOnWrappedObject('__invoke', [$executionContext, $processor])->shouldReturn($pipelineExecution);
    }
}
