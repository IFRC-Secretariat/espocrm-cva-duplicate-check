<?php
/************************************************************************
 * 
 * Adding a custom tool based on Espo/Tools/Import/Api/PostExportErrors.php to
 * allow export of records related to an import.
 *
 ************************************************************************/

namespace Espo\Modules\CVADeDuplication\Tools\Import\Api;

use Espo\Core\Acl;
use Espo\Core\Api\Action;
use Espo\Core\Api\Request;
use Espo\Core\Api\Response;
use Espo\Core\Api\ResponseComposer;
use Espo\Core\Exceptions\BadRequest;
use Espo\Core\Exceptions\Forbidden;
use Espo\Entities\Import;

use Espo\Modules\CVADeDuplication\Services\Import as ImportService;

/**
 * Exports records related to an import.
 */
class PostExportRelationship implements Action
{
	public function __construct(
		private ImportService $importService,
		private Acl $acl
	) {}

    public function process(Request $request): Response
    {
        if (!$this->acl->checkScope(Import::ENTITY_TYPE)) {
            throw new Forbidden();
        }

        $id = $request->getRouteParam('id');
        if (!$id) {
            throw new BadRequest();
        }

        $link = $request->getRouteParam('link');
        if (!$link) {
            throw new BadRequest();
        }

        $attachmentId = $this->importService->exportLinkedRecords($id, $link);

        return ResponseComposer::json(['attachmentId' => $attachmentId]);
    }
}
