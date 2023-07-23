define('custom:import-cash-distribution-data', ['action-handler'], function (Dep) {

    return Dep.extend({
 
         actionImportCashDistributionData: function (data, e) {
            var formData = {};

            formData.entityType = 'CashDistribution';
            formData.attributeList = [];

            formData = Espo.Utils.cloneDeep(formData);

            this.getRouter().navigate('#Import', {trigger: false});

            this.getRouter().dispatch('Import', 'index', {
                formData: formData,
            });
         },
    });
 });