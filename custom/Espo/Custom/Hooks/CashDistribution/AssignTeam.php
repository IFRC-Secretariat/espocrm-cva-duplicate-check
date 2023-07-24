<?php
/************************************************************************
 * This hook sets the "teams" field to be the team of the user creating the record.
 ************************************************************************/

namespace Espo\Custom\Hooks\CashDistribution;

use Espo\ORM\Entity;
use Espo\ORM\EntityManager;
use Espo\Core\Utils\Log;

class AssignTeam
{

    public function __construct(
        private EntityManager $entityManager,
        private Log $log
    ) {
        $this->log = $log;
    }

    public function beforeSave(Entity $entity, array $options): void
    {
        if ($entity->isNew()) { 

            // Get the teams of the user who created the entity
            $createdById = $entity->get('createdById');
            $createdByUser = $this->entityManager->getEntityById('User', $createdById);
            $createdByUserTeams = $createdByUser->getTeamIdList();

            // Set the entity teams to the teams of the created by user
            foreach ($createdByUserTeams as $team) {
                $entity->addLinkMultipleId('teams', $team);
            }
        }
    }
}