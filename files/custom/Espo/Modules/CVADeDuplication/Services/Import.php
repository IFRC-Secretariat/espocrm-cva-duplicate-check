<?php
/************************************************************************
 * Added to generate a list of entities imported, not including duplicates.
 * Used to show list of imported data not including dupilcates on the Import results/ detail page.
 * 
 * Based on the "findLinked" method in "Espo/Services/Import.php".
 ************************************************************************/

namespace Espo\Modules\CVADeDuplication\Services;

use Espo\Core\Acl\Table;
use Espo\Core\Exceptions\Forbidden;
use Espo\Core\Exceptions\NotFoundSilent;
use Espo\Entities\Import as ImportEntity;
use Espo\Core\Record\Collection as RecordCollection;
use Espo\ORM\Query\SelectBuilder;
use Espo\Core\Select\SearchParams;
use Espo\Core\FieldProcessing\ListLoadProcessor;

use Espo\Services\Import as StockImport;

/**
 * @extends Record<ImportEntity>
 */
class Import extends StockImport
{
    public function findLinkedImportedNoDuplicates(string $id, SearchParams $searchParams): RecordCollection
    {   
        /** @var ?ImportEntity $entity */
        $entity = $this->getRepository()->getById($id);

        if (!$entity) {
            throw new NotFoundSilent();
        }

        $foreignEntityType = $entity->get('entityType');

        if (!$this->acl->check($entity, Table::ACTION_READ)) {
            throw new Forbidden();
        }

        if (!$this->acl->check($foreignEntityType, Table::ACTION_READ)) {
            throw new Forbidden();
        }

        $query = $this->selectBuilderFactory
            ->create()
            ->from($foreignEntityType)
            ->withStrictAccessControl()
            ->withSearchParams($searchParams)
            ->build();

        /** @var \Espo\ORM\Collection<\Espo\ORM\Entity> $collection */
        $collection = $this->getRepository()->findResultRecordsImportedNoDuplicates($entity, $query);

        $listLoadProcessor = $this->injectableFactory->create(ListLoadProcessor::class);

        $recordService = $this->recordServiceContainer->get($foreignEntityType);

        foreach ($collection as $e) {
            $listLoadProcessor->process($e);
            $recordService->prepareEntityForOutput($e);
        }

        $total = $this->getRepository()->countResultRecordsImportedNoDuplicates($entity, $query);

        return new RecordCollection($collection, $total);
    }
}
