define('cva-de-duplication:import-cash-distribution-data', ['action-handler'], function (Dep) {

    return Dep.extend({
 
         actionImportCashDistributionData: function (data, e) {
            var formData = {};

            formData.entityType = 'CashDistribution';
            formData.headerRow = true;
            formData.silentMode = true;
            formData.decimalMark = '.';
            formData.manualMode = false;

            this.view.getRouter().navigate('#Import', {trigger: false});

            this.view.getRouter().dispatch('Import', 'index', {
                formData: formData,
            });
         },
    });
 });