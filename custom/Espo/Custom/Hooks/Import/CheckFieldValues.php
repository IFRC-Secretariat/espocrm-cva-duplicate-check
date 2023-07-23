<?php
/************************************************************************
 * This hook runs validation checks on the import before it is saved.
 * These includes checking that:
 * - The entity type is a valid one for import (CashDistribution or DuplicateCheck)
 * - The action is create only (not update)
 * - Duplicate checking is not set to skip
 * - All selected fields are custom or "name" (not built-in fields)
 * - No selected fields are read-only fields
 * - Fields aren't selected multiple times in the field mapping
 * - Required fields are set in the field mapping 
 ************************************************************************/

namespace Espo\Custom\Hooks\Import;

use Espo\ORM\Entity;
use Espo\ORM\EntityManager;
use Espo\Core\Utils\Log;
use Espo\Core\Utils\Language;
use Espo\Core\Exceptions\Error;

class CheckFieldValues
{

    public function __construct(
        private EntityManager $entityManager,
        private Language $language,
        private Log $log
    ) {
        $this->log = $log;
    }

    public function beforeSave(Entity $entity, array $options): void
    {
        if ($entity->isNew()) {

            // Check that the entity type is CashDistribution or DuplicateCheck
            $entityType = $entity->get('entityType');
            if (($entityType!="CashDistribution") And ($entityType!="DuplicateCheck")) {
                throw new Error("Import not allowed for {$entityType}.");
            }
            
            // Check that the action is Create only
            $action = $entity->get('params')->action;
            if ($action!="create") {
                throw new Error("Update not allowed.");
            }

            // Check that duplicate checking will be run
            $duplicateCheck = $entity->get('params')->skipDuplicateChecking;
            if (!empty($duplicateCheck)) {
                throw new Error("Duplicate check must be enabled.");
            }

            // Validate fields mapping
            $selectedFields = array_filter($entity->get("attributeList"), function($field) { return !is_null($field) && $field !== ''; });
            $entityDefs = $this->entityManager->getDefs()->getEntity($entity->get('entityType'));
            $importEntityFieldList = $entityDefs->getFieldList();
            $customFields = array(); $readOnlyFields = array(); $requiredFields = array();
            foreach ($importEntityFieldList as $field) {
                $fieldName = $field->getName();
                if ($field->getParam('isCustom')) {
                    $customFields[] = $fieldName;
                }
                if ($field->getParam('readOnly')) {
                    $readOnlyFields[] = $fieldName;
                }
                if ($field->getParam('required')) {
                    $fieldLabel = $this->language->translate($fieldName, 'fields', $entityType);
                    $requiredFields[$fieldLabel] = $fieldName;
                }
            }
            // Check that all selected fields are custom fields (not built-in fields like createdAt, etc.), except 'name'
            // Check that no selected fields are read-only fields
            //$notAllowedFields = array("assignedUserName", "assignedUserId", "createdAt", "createdById", "createdByName", "description", "id", "modifiedAt", "modifiedById", "modifiedByName", "teamsIds");
            $nonCustomFields = array_diff($selectedFields, $customFields, array("name"));
            $readOnlyFields = array_intersect($selectedFields, $readOnlyFields);
            $invalidFields = array_unique(array_merge($nonCustomFields, $readOnlyFields));
            if (!empty($invalidFields)) {
                $invalidFieldsString = implode(", ", $invalidFields);
                throw new Error("Invalid fields: {$invalidFieldsString}.");
            }

            // Check that fields aren't selected multiple times
            if (count($selectedFields) != count(array_unique($selectedFields))) {
                $multipleFields = array_filter(array_count_values($selectedFields), function($count){
                    return ($count > 1);
                });
                $multipleFieldsLabels = array_map(function($fieldName) use ($entityType) {
                    return $this->language->translate($fieldName, 'fields', $entityType);
                }, array_keys($multipleFields));
                $multipleFieldsLabelsString = implode(", ", array_values($multipleFieldsLabels));
                throw new Error("Repeated fields: {$multipleFieldsLabelsString}.");
            }

            // Check that all required fields are not blank
            $missingRequiredFields = array_diff($requiredFields, $selectedFields);
            if (!empty($missingRequiredFields)) {
                $missingRequiredFieldsString = implode(", ", array_keys($missingRequiredFields));
                throw new Error("Missing required fields: {$missingRequiredFieldsString}.");
            }
        }
    }
}