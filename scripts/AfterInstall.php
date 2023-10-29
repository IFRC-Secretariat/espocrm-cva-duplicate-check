<?php

class AfterInstall
{
    public function run($container)
    {
        $config = $container->get('config');
        $tabList = $config->get('tabList', []);

        if (!in_array('CashDistribution', $tabList)) {
            array_unshift($tabList, 'CashDistribution');
        }
        if (!in_array('DuplicateCheck', $tabList)) {
            array_unshift($tabList, 'DuplicateCheck');
        }
        $config->set('tabList', $tabList);
        $config->save();
    }
}