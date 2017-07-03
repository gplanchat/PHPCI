<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="kiboko_pipeline",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"identifier"})
 *     }
 * )
 */
class PipelineExecution
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="uuid_binary")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @var UuidInterface
     */
    private $identifier;

    /**
     * @ORM\OneToMany(targetEntity="Build", mappedBy="pipelines", cascade={"persist", "remove", "merge"}, orphanRemoval=false)
     *
     * @var BuildInterface
     */
    private $build;

    /**
     * @ORM\OneToMany(targetEntity="StepExecution", mappedBy="pipeline", cascade={"persist", "remove", "merge"}, orphanRemoval=false)
     *
     * @var StepExecution
     */
    private $stepExecutions;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return PipelineExecution
     */
    public function setId(int $id): PipelineExecution
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return UuidInterface
     */
    public function getIdentifier(): UuidInterface
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     *
     * @return PipelineExecution
     */
    public function setIdentifier(UuidInterface $identifier): PipelineExecution
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return BuildInterface
     */
    public function getBuild(): BuildInterface
    {
        return $this->build;
    }

    /**
     * @param BuildInterface $build
     *
     * @return PipelineExecution
     */
    public function setBuild(BuildInterface $build): PipelineExecution
    {
        $this->build = $build;

        return $this;
    }
}
