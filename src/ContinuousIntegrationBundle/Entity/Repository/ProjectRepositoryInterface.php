<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectRepository;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\ProjectGroupInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\ProjectInterface;
use Ramsey\Uuid\UuidInterface;

interface ProjectRepositoryInterface extends ObjectRepository
{
    /**
     * @param UuidInterface $identifier
     *
     * @return ProjectInterface
     */
    public function findOneById(UuidInterface $identifier): ProjectInterface;

    /**
     * @param UuidInterface $identifier
     *
     * @return ProjectInterface
     */
    public function findOneByIdentifier(UuidInterface $identifier): ProjectInterface;

    /**
     * @param ProjectGroupInterface $projectGroup
     *
     * @return Collection|ProjectInterface[]
     */
    public function findByGroup(ProjectGroupInterface $projectGroup): Collection;
}
