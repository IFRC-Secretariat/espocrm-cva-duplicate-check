define('custom:import-duplicate-check-data', ['action-handler'], function (Dep) {

    return Dep.extend({
 
         actionImportDuplicateCheckData: function (data, e) {
            var formData = {};

            formData.entityType = 'DuplicateCheck';
            formData.headerRow = true;
            formData.silentMode = true;
            formData.decimalMark = '.';

            this.getRouter().navigate('#Import', {trigger: false});

            this.getRouter().dispatch('Import', 'index', {
                formData: formData,
            });
         },
    });
 });