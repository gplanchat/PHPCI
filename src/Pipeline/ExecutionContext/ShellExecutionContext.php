<?php

namespace Kiboko\Component\Pipeline\ExecutionContext;

use Kiboko\Component\Pipeline\ExecutionContext\Command\CommandInterface;
use Symfony\Component\Process\Process;

class ShellExecutionContext implements ExecutionContextInterface
{
    /**
     * @var string
     */
    private $workingDirectory;

    /**
     * ShellExecutionContext constructor.
     *
     * @param string $workingDirectory
     */
    public function __construct(
        ?string $workingDirectory = null
    ) {
        $this->workingDirectory = $workingDirectory ?: getcwd();
    }

    /**
     * @return string
     */
    public function workingDirectory(): ?string
    {
        return $this->workingDirectory;
    }

    /**
     * @param CommandInterface $command
     *
     * @return Process
     */
    public function build(CommandInterface $command): Process
    {
        $processBuilder = new ProcessBuilder($command);
        $processBuilder->setWorkingDirectory($this->workingDirectory());

        return $processBuilder->getProcess();
    }
}
