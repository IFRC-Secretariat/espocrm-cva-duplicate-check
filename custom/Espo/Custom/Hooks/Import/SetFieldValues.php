<?php
/************************************************************************
 * This hook sets the value of calculated fields:
 * - Action
 ************************************************************************/

namespace Espo\Custom\Hooks\Import;

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

            // Set the action field based on the entity type
            $actionFieldValues = array(
                "CashDistribution" => $this->language->translate("CashDistribution", 'importEntityDescriptive', 'Import'),
                "DuplicateCheck" => $this->language->translate("DuplicateCheck", 'importEntityDescriptive', 'Import'),
            );
            $entityType = $entity->get('entityType');
            if (!empty($entityType)) {
                if (array_key_exists($entityType, $actionFieldValues)) {
                    $entity->set('action', $actionFieldValues[$entityType]);
                }
            }
        }
    }
}