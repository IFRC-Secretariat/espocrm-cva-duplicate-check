<?php
/************************************************************************
* This file is based on the EspoCRM duplication process in version 7.5.4.
* This file is based on: Espo\Core\Duplicate\Finder
************************************************************************/
namespace Espo\Modules\CVADeDuplication\DuplicateCheckEntityType;

use Espo\ORM\Entity;
use Espo\ORM\EntityManager;
use Espo\ORM\Query\Part\Condition as Cond;
use Espo\ORM\Query\Part\WhereItem;
use Espo\Core\Duplicate\WhereBuilderFactory;
use Espo\Core\Duplicate\WhereBuilder;
use Espo\Core\Utils\Log;

class Finder
{
    private const LIMIT = 10;

    /** @var array<string, ?WhereBuilder<Entity>> */
    private array $whereBuilderMap = [];

    public function __construct(
        private EntityManager $entityManager,
        private WhereBuilderFactory $whereBuilderFactory,
        private Log $log
    ) {
        $this->log = $log;
    }

    /**
     * Check whether an entity has a duplicate.
     */
    public function check(Entity $entity, string $entityType): bool
    {

        $where = $this->getWhere($entity, $entityType);

        if (!$where) {
            return false;
        }

        return $this->checkByWhere($entity, $entityType, $where);
    }

    /**
     * The method is public for backward compatibility.
     */
    public function checkByWhere(Entity $entity, string $entityType, WhereItem $where): bool
    {

        if ($entity->hasId()) {
            $where = Cond::and(
                $where,
                Cond::notEqual(
                    Cond::column('id'),
                    $entity->getId()
                )
            );
        }

        $duplicate = $this->entityManager
            ->getRDBRepository($entityType)
            ->where($where)
            ->select('id')
            ->findOne();

        return (bool) $duplicate;
    }

    private function getWhere(Entity $entity, string $entityType): ?WhereItem
    {

        if (!array_key_exists($entityType, $this->whereBuilderMap)) {
            $this->whereBuilderMap[$entityType] = $this->loadWhereBuilder($entityType);
        }

        $builder = $this->whereBuilderMap[$entityType];

        return $builder?->build($entity);
    }

    /**
     * @return ?WhereBuilder<Entity>
     */
    private function loadWhereBuilder(string $entityType): ?WhereBuilder
    {
        if (!$this->whereBuilderFactory->has($entityType)) {
            return null;
        }

        return $this->whereBuilderFactory->create($entityType);
    }
}
