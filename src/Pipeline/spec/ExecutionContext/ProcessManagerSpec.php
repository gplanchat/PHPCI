<?php

namespace spec\Kiboko\Component\Pipeline\ExecutionContext;

use Kiboko\Component\Pipeline\ExecutionContext\ProcessManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;
use Symfony\Component\Process\Process;

class ProcessManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(1);
        $this->shouldHaveType(ProcessManager::class);
    }

    function it_launches_several_processes(
        Process $processA,
        Process $processB
    ) {
        $this->beConstructedWith(1);

        $processA->isRunning()->shouldBeCalled()->willReturn(true, false);
        $processB->isRunning()->shouldBeCalled()->willReturn(true, false);

        $processA->start()->shouldBeCalled();
        $processB->start()->shouldBeCalled();

        $this->enqueue($processA);
        $this->enqueue($processB);

        $this->run(function(ProcessManager $manager, array $stoppedProcesses) {
            return $manager->count() > 0;
        });
    }
}
