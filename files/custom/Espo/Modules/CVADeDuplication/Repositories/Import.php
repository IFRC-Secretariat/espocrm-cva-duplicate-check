<?php
/************************************************************************
 * Added to generate a list of entities imported, not including duplicates.
 * Used to show list of imported data not including dupilcates on the Import results/ detail page.
 * 
 * Based on the file: "Espo/Repostitories/Import.php".
 * Adds new method specific to getting imported data with no duplicates.
 ************************************************************************/

namespace Espo\Modules\CVADeDuplication\Repositories;

use Espo\Entities\Import as ImportEntity;
use Espo\Entities\ImportEntity as ImportEntityEntity;

use Espo\ORM\Collection;
use Espo\ORM\Entity;
use Espo\ORM\Query\Select as Query;
use Espo\ORM\Query\SelectBuilder;

use Espo\Entities\Attachment as AttachmentEntity;

use Espo\Core\Repositories\Database;
use Espo\Entities\ImportError;

use Espo\Repositories\Import as ImportStock;

use LogicException;

/**
 * @extends Database<\Espo\Entities\Import>
 */
class Import extends ImportStock
{
    public function findResultRecordsImportedNoDuplicates(ImportEntity $entity, Query $query): Collection
    {
        $entityType = $entity->getTargetEntityType();

        if (!$entityType) {
            throw new LogicException();
        }

        $modifiedQuery = $this->addImportEntityJoinImportedNoDuplicates($entity, $query);

        return $this->entityManager
            ->getRDBRepository($entityType)
            ->clone($modifiedQuery)
            ->find();
    }

    protected function addImportEntityJoinImportedNoDuplicates(ImportEntity $entity, Query $query): Query
    {
        $entityType = $entity->getTargetEntityType();

        if (!$entityType) {
            throw new LogicException();
        }

        $builder = SelectBuilder::create()->clone($query);

        $builder->join(
            'ImportEntity',
            'importEntity',
            [
                'importEntity.importId' => $entity->getId(),
                'importEntity.entityType' => $entityType,
                'importEntity.entityId:' => 'id',
                'importEntity.isImported' => true,
                'importEntity.isDuplicate' => false,
            ]
        );

        return $builder->build();
    }

    public function countResultRecordsImportedNoDuplicates(ImportEntity $entity, ?Query $query = null): int
    {
        $entityType = $entity->getTargetEntityType();

        if (!$entityType) {
            throw new LogicException();
        }

        $query = $query ??
            $this->entityManager
            ->getQueryBuilder()
            ->select()
            ->from($entityType)
            ->build();

        $modifiedQuery = $this->addImportEntityJoinImportedNoDuplicates($entity, $query);

        return $this->entityManager
            ->getRDBRepository($entityType)
            ->clone($modifiedQuery)
            ->count();
    }
}
