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
        $config->set('dashboardLayout', []);
        $config->set('theme', 'Hazyblue');
        $config->set('recordsPerPage', 200);
        $config->set('recordsPerPageSmall', 100);
        $config->set('recordsPerPageSelect', 100);
        $config->set('recordsPerPageKanban', 100);
        $config->set('theme', 'RCRC');

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