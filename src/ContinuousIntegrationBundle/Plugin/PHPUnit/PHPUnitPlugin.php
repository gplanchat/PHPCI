<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\PHPUnit;

use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\StepInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\PluginConfigurationInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\PluginInterface;
use Psr\Log\LoggerInterface;

class PHPUnitPlugin implements PluginInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * DockerPlugin constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return PluginConfigurationInterface
     */
    public function getConfiguration(): PluginConfigurationInterface
    {
        return new Configuration();
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return 'phpunit';
    }

    /**
     * @param array $stepConfig
     *
     * @return StepInterface
     */
    public function buildStep(array $stepConfig): StepInterface
    {
        return new PHPUnitStep($this->logger, $stepConfig);
    }
}
