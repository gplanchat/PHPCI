<?php

namespace Kiboko\Component\ContinuousIntegration\Worker;

use b8\Config;
use b8\Database;
use b8\Store\Factory;
use Pheanstalk\Job;
use Kiboko\Component\ContinuousIntegration\Builder;
use Kiboko\Component\ContinuousIntegration\BuildFactory;
use Kiboko\Component\ContinuousIntegration\Logging\BuildDBLogHandler;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\Build;
use Kiboko\Bundle\ContinuousIntegrationBundle\Worker\WorkerInterface;
use Pheanstalk\PheanstalkInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class BuildWorker implements WorkerInterface, LoggerAwareInterface
{
    /**
     * @var bool
     */
    private $shouldStop;

    /**
     * @var int
     */
    private $maximumJobs;

    /**
     * @var int
     */
    private $totalJobs;

    /**
     * @var PheanstalkInterface
     */
    private $pheanstalk;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * beanstalkd host
     * @var string
     */
    protected $host;

    /**
     * beanstalkd queue to watch
     * @var string
     */
    protected $queue;

    /**
     * @param $host
     * @param $queue
     */
    public function __construct($host, $queue)
    {
        $this->host = $host;
        $this->queue = $queue;
        $this->pheanstalk = new Pheanstalk($this->host);
    }

    /**
     * @param int $maxJobs
     */
    public function setMaxJobs($maxJobs = -1)
    {
        $this->maxJobs = $maxJobs;
    }

    /**
     * Start the worker.
     */
    public function startWorker()
    {
        $this->pheanstalk->watch($this->queue);
        $this->pheanstalk->ignore('default');
        $buildStore = Factory::getStore('Build');

        while ($this->shouldStop !== true) {
            /** @var Job $job */
            $job = $this->pheanstalk->reserve();

            $this->checkJobLimit();

            // Get the job data and run the job:
            $jobData = json_decode($job->getData(), true);

            if (!$this->validateJob($job, $jobData)) {
                continue;
            }

            $this->logger->addInfo('Received build #'.$jobData['build_id'].' from Beanstalkd');

            // If the job comes with config data, reset our config and database connections
            // and then make sure we kill the worker afterwards:
            if (!empty($jobData['config'])) {
                $this->logger->addDebug('Using job-specific config.');
                $currentConfig = Config::getInstance()->getArray();
                $config = new Config($jobData['config']);
                Database::reset($config);
            }

            try {
                $build = BuildFactory::getBuildById($jobData['build_id']);
            } catch (\Exception $ex) {
                $this->logger->addWarning('Build #' . $jobData['build_id'] . ' does not exist in the database.');
                $this->pheanstalk->delete($job);
            }

            try {
                // Logging relevant to this build should be stored
                // against the build itself.
                $buildDbLog = new BuildDBLogHandler($build, LogLevel::INFO);
                $this->logger->pushHandler($buildDbLog);

                $builder = new Builder($build, $this->logger);
                $builder->execute();

                // After execution we no longer want to record the information
                // back to this specific build so the handler should be removed.
                $this->logger->popHandler($buildDbLog);
            } catch (\PDOException $ex) {
                // If we've caught a PDO Exception, it is probably not the fault of the build, but of a failed
                // connection or similar. Release the job and kill the worker.
                $this->run = false;
                $this->pheanstalk->release($job);
            } catch (\Exception $ex) {
                $build->setStatus(Build::STATUS_FAILED);
                $build->setFinished(new \DateTime());
                $build->setLog($build->getLog() . PHP_EOL . PHP_EOL . $ex->getMessage());
                $buildStore->save($build);
                $build->sendStatusPostback();
            }

            // Reset the config back to how it was prior to running this job:
            if (!empty($currentConfig)) {
                $config = new Config($currentConfig);
                Database::reset($config);
            }

            // Delete the job when we're done:
            $this->pheanstalk->delete($job);
        }
    }

    public function stop(): void
    {
        $this->shouldStop = true;
    }

    /**
     * @return LoggerInterface
     */
    protected function checkJobLimit()
    {
        // Make sure we don't run more than maxJobs jobs on this worker:
        $this->totalJobs++;

        if ($this->maxJobs != -1 && $this->maxJobs <= $this->totalJobs) {
            $this->stopWorker();
        }
    }

    /**
     * Checks that the job received is actually from Kiboko CI, and has a valid type.
     * @param Job $job
     * @param $jobData
     * @return bool
     */
    protected function validateJob(Job $job, array $jobData)
    {
        if (empty($jobData)) {
            $this->pheanstalk->delete($job);
            return false;
        }

        if (!isset('type', $jobData) || $jobData['type'] !== 'phpci.build') {
            $this->pheanstalk->delete($job);
            return false;
        }

        return true;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return WorkerInterface
     */
    public function setLogger(LoggerInterface $logger): WorkerInterface
    {
        $this->logger = $logger;

        return $this;
    }
}
