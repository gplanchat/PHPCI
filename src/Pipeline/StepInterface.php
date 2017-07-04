<?php

namespace Kiboko\Component\Pipeline;

use Kiboko\Component\Pipeline\ExecutionContext\ExecutionContextInterface;
use Kiboko\Component\Pipeline\ExecutionContext\ProcessManager;

interface StepInterface
{
    /**
     * @param ProcessManager $processManager
     * @param ExecutionContextInterface $executionContext
     *
     * @return ExecutionContextInterface
     */
    public function __invoke(
        ProcessManager $processManager,
        ExecutionContextInterface $executionContext
    ): ExecutionContextInterface;
}
