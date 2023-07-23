<?php
/************************************************************************
* This file is based on the EspoCRM duplication process in version 7.5.4.
* This file is based on Espo\Core\Record\Service
************************************************************************/
namespace Espo\Custom\DuplicateCheckEntityType;

use Espo\ORM\Entity;
use Espo\Core\Utils\Log;
use Espo\Custom\DuplicateCheckEntityType\Finder as DuplicateFinder;
use Espo\Core\InjectableFactory;

class Service
{
    private ?DuplicateFinder $duplicateFinder = null;

    public function __construct(
        private Log $log,
        private InjectableFactory $injectableFactory,
    ) {
        $this->log = $log;
        $this->injectableFactory = $injectableFactory;
    }

    /**
     * Check whether an entity has a duplicate.
     * Copy of the function defined in: application/Espo/Core/Record/Service.php Ln 1558
     * Remove checking for the 'getDuplicateWhereClause' method as this is not defined here
     *
     * @param Entity $entity
     */
    public function checkIsDuplicate(Entity $entity, string $entityType): bool
    {
        $finder = $this->getDuplicateFinder();

        return $finder->check($entity, $entityType);
    }

    /**
     * Copy of the function defined in: application/Espo/Core/Record/Service.php Ln 1544
     *
     * @param Entity $entity
     */
    private function getDuplicateFinder(): DuplicateFinder
    {
        if (!$this->duplicateFinder) {
            $this->duplicateFinder = $this->injectableFactory->create(DuplicateFinder::class);
        }

        return $this->duplicateFinder;
    }

}