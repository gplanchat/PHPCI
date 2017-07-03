<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\ProjectGroupInterface;
use Ramsey\Uuid\UuidInterface;

class ProjectGroupRepository extends EntityRepository implements ProjectGroupRepositoryInterface
{
    /**
     * @param UuidInterface $identifier
     *
     * @return ProjectGroupInterface
     */
    public function findOneById(UuidInterface $identifier): ProjectGroupInterface
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
     * @return ProjectGroupInterface
     */
    public function findOneByIdentifier(UuidInterface $identifier): ProjectGroupInterface
    {
        return $this->findOneBy(
            [
                'identifier' => $identifier
            ]
        );
    }
}
