<?php

namespace Kiboko\Component\Pipeline\ExecutionContext;

use Kiboko\Component\Pipeline\Entity\BuildInterface;
use Kiboko\Component\Pipeline\ExecutionContext\Command\CommandInterface;
use Symfony\Component\Process\Process;

interface ExecutionContextInterface
{
    /**
     * @return string
     */
    public function workingDirectory(): ?string;

    /**
     * @param CommandInterface $command
     *
     * @return Process
     */
    public function build(CommandInterface $command): Process;
}
