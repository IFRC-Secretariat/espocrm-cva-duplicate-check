<?php
/************************************************************************
 * This hook sets the value of calculated fields:
 * - Action
 ************************************************************************/

namespace Espo\Modules\CVADeDuplication\Hooks\Import;

use Espo\ORM\Entity;
use Espo\Core\Utils\Log;
use Espo\Core\Utils\Language;

class SetFieldValues
{
    public static int $order = 10;

    public function __construct(
        private Language $language,
        private Log $log
    ) {
        $this->log = $log;
    }

    public function beforeSave(Entity $entity, array $options): void
    {
        if ($entity->isNew()) {
            $entityType = $entity->get('entityType');
            if (!empty($entityType)) {
                $entity->set('action', $this->language->translate($entityType, 'importEntityDescriptive', 'Import'));
            }
        }
    }
}