<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="kiboko_step",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"identifier"})
 *     }
 * )
 */
class StepExecution
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return StepExecution
     */
    public function setId(int $id): StepExecution
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
     * @return StepExecution
     */
    public function setIdentifier(UuidInterface $identifier): StepExecution
    {
        $this->identifier = $identifier;

        return $this;
    }
}
