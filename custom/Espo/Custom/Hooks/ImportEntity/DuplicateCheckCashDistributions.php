<?php
/************************************************************************
 * This file mimics the duplication functionality of EspoCRM v7.5.4.
 * 
 * It uses a custom checkIsDuplicate function defined in 
 * Espo\Custom\DuplicateCheckEntityType\Service to compare $entity 
 * (ImportEntity) to data of a different entity type (CashDistribution).
 * It passes a parameter $entityType to the checkIsDuplicate method which
 * specifies the entity type to compare $entity to.
 * The structure of the usual EspoCRM duplicate check is followed, with
 * simplifications to include only required functionality.
 * 
 * See Espo\Tools\Import\Import.php for more details of the usual 
 * duplication check process.
 ************************************************************************/
namespace Espo\Custom\Hooks\ImportEntity;

use Espo\ORM\Entity;
use Espo\ORM\EntityManager;
use Espo\Core\Utils\Log;
use Espo\Custom\DuplicateCheckEntityType\Service;


class DuplicateCheckCashDistributions
{

    public function __construct(
        private EntityManager $entityManager,
        private Service $recordService,
        private Log $log,
    ) {
        $this->log = $log;
        $this->recordService = $recordService;
    }

    public function beforeSave(Entity $entity, array $options): void
    {
        if ($entity->isNew()) {

            // Check that the type of entity being imported is DuplicateCheck
            $importEntityType = $entity->get('entityType');
            if ($importEntityType=="DuplicateCheck") {

                // Get the DuplicateCheck Entity
                $duplicateCheckEntity = $this->entityManager->getEntityById($importEntityType, $entity->get('entityId'));

                // Duplicate check: compare the DuplicateCheck entity to the CashDistribution data
                $compareToEntityType = "CashDistribution";
                $isDuplicate = $this->recordService->checkIsDuplicate($duplicateCheckEntity, $compareToEntityType);

                // Set isDuplicate property of ImportEntity
                $entity->set('isDuplicate', $isDuplicate);

            }
        }
    }
}