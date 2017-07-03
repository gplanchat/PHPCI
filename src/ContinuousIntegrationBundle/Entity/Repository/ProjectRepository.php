<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\ProjectGroupInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\ProjectInterface;
use Ramsey\Uuid\UuidInterface;

class ProjectRepository extends EntityRepository implements ProjectRepositoryInterface
{
    /**
     * @param UuidInterface $identifier
     *
     * @return ProjectInterface
     */
    public function findOneById(UuidInterface $identifier): ProjectInterface
    {
        return $this->findOneBy(
            [
                'id' => $identifier
            ]
        );
    }

    /**
     * @param UuidInterface $identifier
     *
     * @return ProjectInterface
     */
    public function findOneByIdentifier(UuidInterface $identifier): ProjectInterface
    {
        return $this->findOneBy(
            [
                'identifier' => $identifier
            ]
        );
    }

    /**
     * @param ProjectGroupInterface $projectGroup
     *
     * @return ProjectInterface[]|Collection
     */
    public function findByGroup(ProjectGroupInterface $projectGroup): Collection
    {
        return $this->findOneBy(
            [
                'group' => $projectGroup
            ]
        );
    }
}
