<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Plugin;

use Kiboko\Component\ContinuousIntegration;
use Kiboko\Component\ContinuousIntegration\Builder;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\Build;

/**
 * PHP Loc - Allows PHP Copy / Lines of Code testing.
 * @author       Johan van der Heide <info@japaveh.nl>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class PhpLoc implements ContinuousIntegration\Plugin, ContinuousIntegration\ZeroConfigPlugin
{
    /**
     * @var string
     */
    protected $directory;
    /**
     * @var \Kiboko\Component\ContinuousIntegration\Builder
     */
    protected $phpci;

    /**
     * Check if this plugin can be executed.
     * @param $stage
     * @param Builder $builder
     * @param Build $build
     * @return bool
     */
    public static function canExecute($stage, Builder $builder, Build $build)
    {
        if ($stage == 'test') {
            return true;
        }

        return false;
    }

    /**
     * Set up the plugin, configure options, etc.
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci     = $phpci;
        $this->build     = $build;
        $this->directory = $phpci->buildPath;

        if (isset($options['directory'])) {
            $this->directory .= $options['directory'];
        }
    }

    /**
     * Runs PHP Copy/Paste Detector in a specified directory.
     */
    public function execute()
    {
        $ignore = '';

        if (count($this->phpci->ignore)) {
            $map = function ($item) {
                return ' --exclude ' . rtrim($item, DIRECTORY_SEPARATOR);
            };

            $ignore = array_map($map, $this->phpci->ignore);
            $ignore = implode('', $ignore);
        }

        $phploc = $this->phpci->findBinary('phploc');

        $success = $this->phpci->executeCommand($phploc . ' %s "%s"', $ignore, $this->directory);
        $output  = $this->phpci->getLastOutput();

        if (preg_match_all('/\((LOC|CLOC|NCLOC|LLOC)\)\s+([0-9]+)/', $output, $matches)) {
            $data = array();
            foreach ($matches[1] as $k => $v) {
                $data[$v] = (int)$matches[2][$k];
            }

            $this->build->storeMeta('phploc', $data);
        }

        return $success;
    }
}
