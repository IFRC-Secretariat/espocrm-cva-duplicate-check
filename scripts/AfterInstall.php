<?php

class AfterInstall
{
    protected $container;

    public function run($container)
    {
        $this->container = $container;
        $config = $this->container->get('config');
        #$tabList = $config->get('tabList', []);
        $tabList = [];

        if (!in_array('CashDistribution', $tabList)) {
            array_push($tabList, 'CashDistribution');
        }
        array_push($tabList, 'Import', 'User', 'Team');
        $config->set('tabList', $tabList);
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