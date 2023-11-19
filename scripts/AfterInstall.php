<?php

class AfterInstall
{
    protected $container;

    public function run($container)
    {
        $this->container = $container;
        $config = $this->container->get('config');

        # Set the tab list, quick create list, and global search list
        $config->set('tabList', ['CashDistribution', 'Import', 'User', 'Team']);
        $config->set('quickCreateList', []);
        $config->set('globalSearchEntityList', ['CashDistribution', 'User']);

        # Set other user interface config options
        $config->set('passwordGenerateLength', 16);
        $config->set('passwordStrengthLength', 16);
        $config->set('passwordStrengthBothCases', true);
        $config->set('dashboardLayout', []);
        $config->set('theme', 'Hazyblue');
        $config->set('recordsPerPage', 200);
        $config->set('recordsPerPageSmall', 100);
        $config->set('recordsPerPageSelect', 100);
        $config->set('recordsPerPageKanban', 100);
        $config->set('theme', 'RCRC');
        $config->set('userThemesDisabled', true);
        $config->set('avatarsDisabled', true);

        # Set currency settings
        $config->set('currencyList', [0 => 'SYP']);
        $config->set('defaultCurrency', 'SYP');
        $config->set('baseCurrency', 'SYP');
        $config->set('currencyRates', []);
        $config->set('currencyFormat', 1);
        $config->set('currencyDecimalPlaces', 2);

        # Create a Partner role entity
        $entityManager = $container->get('entityManager');
        $partnerRole = $entityManager->getRepository('Role')->where(['name' => 'Partner'])->findOne();
        if (!$partnerRole) {
            $partnerRole = $entityManager->getEntity('Role');
        }
        $partnerRole->set([
            'name' => 'Partner',
            'exportPermission' => 'yes',
            'userPermission' => 'no',
            'assignmentPermission' => 'no',
            'portalPermission' => 'no',
            'groupEmailAccountPermission' => 'no',
            'dataPrivacyPermission' => 'no',
            'massUpdatePermission' => 'no',
            'followerManagementPermission' => 'no',
            'messagePermission' => 'no',
            'data' => array(
                "Activities" => false,
                "Calendar" => false,
                "CashDistribution" => array(
                    "create" => "yes",
                    "read" => "team",
                    "edit" => "team",
                    "delete" => "team"
                ),
                "Currency" => false,
                "DuplicateCheck" => array(
                    "create" => "yes",
                    "read" => "team",
                    "edit" => "team",
                    "delete" => "no"
                ),
                "EmailTemplateCategory" => false,
                "EmailTemplate" => false,
                "Email" => false,
                "ExternalAccount" => false,
                "EmailAccountScope" => false,
                "Team" => false,
                "Import" => true,
                "User" => array(
                    "read" => "team",
                    "delete" => "no"
                ),
                "Webhook" => false,
                "WorkingTimeCalendar" => false
            ),
            'fieldData' => array(
                "CashDistribution" => array(),
                'DuplicateCheck' => array(),
                'User' => array(),
            )
         ]);
         $entityManager->saveEntity($partnerRole);

        # Save changes and clear cache
        $config->save();
        $this->clearCache();
    }
    
    protected function clearCache()
    {
        try {
            $this->container->get('dataManager')->clearCache();
        } catch (\Exception $e) {}
    }
}