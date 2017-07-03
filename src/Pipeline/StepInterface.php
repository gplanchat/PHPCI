<?php

namespace Kiboko\Component\Pipeline\Pipeline;

use Kiboko\Component\Pipeline\ExecutionContext\ExecutionContextInterface;

interface StepInterface
{
    /**
     * @param ExecutionContextInterface $executionContext
     *
     * @return ExecutionContextInterface
     */
    public function __invoke(
        ExecutionContextInterface $executionContext
    ): ExecutionContextInterface;
}
