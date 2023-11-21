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
use Espo\Tools\Export\Export as ExportTool;
use Espo\Tools\Export\Params as ExportParams;

use Espo\Services\Import as StockImport;

use Espo\Modules\CVADeDuplication\Entities\CashDistribution;
use Espo\Modules\CVADeDuplication\Entities\DuplicateCheck;


/**
 * @extends Record<ImportEntity>
 */
class Import extends StockImport
{
    public function __construct(
	 private ExportTool $exportTool
    ) {
        parent::__construct();
    }

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

    /**
     * Export the records linked to a specified Import entity.
     *
     * @param string $importId An import ID.
     * @param string $link The relation to export.
     * @return ?string An attachment ID.
     */
    public function exportLinkedRecords(string $importId, string $link): ?string
    {

	if ($this->acl->getPermissionLevel('exportPermission') !== Table::LEVEL_YES) {
	        throw new ForbiddenSilent("User has no 'export' permission.");
	}

	// Return all records in ascending order of creation.
	$searchParams = SearchParams::create()
	    ->withOrderBy('createdAt')
	    ->withOrder(SearchParams::ORDER_ASC);
	   
        // Get the right records depending on the link.
	if ($link == 'importedNoDuplicates') {
	    $linkedRecords = $this->findLinkedImportedNoDuplicates(
		    $importId,
		    $searchParams);
	} else {
	    $linkedRecords = $this->findLinked(
		    $importId,
		    $link,
		    $searchParams);
	}

	// If we have no records to show, don't generate an attachment.
	if ($linkedRecords->getTotal() === 0) {
	    return null;
	}

	//
	// Generate an attachment from this record collection using the export mechanism.
	//

	$exportEntityType = $linkedRecords->getCollection()->getEntityType();
	
	// Limit the field list for some known types.
	if ($exportEntityType == CashDistribution::ENTITY_TYPE) {
	    $fieldList = array('name', 'governorate', 'date', 'transferValue');
	} else if ($exportEntityType == DuplicateCheck::ENTITY_TYPE) {
	    $fieldList = array('name');
	} else {
	    // Include all fields.
	    $fieldList = null;
	}
	$exportParams = ExportParams::create($exportEntityType)
	        ->withFormat('csv')
	        ->withAccessControl()
	        ->withFieldList($fieldList);

	$attachment_id = $this->exportTool
	        ->setParams($exportParams)
	        ->setCollection($linkedRecords->getCollection())
	        ->run()
	        ->getAttachmentId();

	return $attachment_id;

    }
}
