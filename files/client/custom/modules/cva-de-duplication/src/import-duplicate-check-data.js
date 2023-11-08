define('cva-de-duplication:import-duplicate-check-data', ['action-handler'], function (Dep) {

    return Dep.extend({
 
         actionImportDuplicateCheckData: function (data, e) {
            var formData = {};

            formData.entityType = 'DuplicateCheck';
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