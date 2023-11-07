/************************************************************************
 * Added to show a new panel on the Import results/ detail page, 
 * showing the list of data that was imported, but not including duplicates.
 * 
 * Based on file: "client/src/views/import/record/panels/imported.js"
 * Link set to: "importedNoDuplicates"
 ************************************************************************/

define('cva-de-duplication:views/import/record/panels/imported-no-duplicates', ['views/record/panels/relationship'], function (Dep) {

    return Dep.extend({

        link: 'importedNoDuplicates',
        readOnly: true,
        rowActionsView: 'views/record/row-actions/relationship-no-unlink',

        setup: function () {
            this.scope = this.model.get('entityType');
            this.title = this.title || this.translate('importedNoDuplicates', 'labels', 'Import');

            Dep.prototype.setup.call(this);
        },
    });
});
