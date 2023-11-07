/************************************************************************
 * Update the fetchResultsPanels function to include the new view 
 * imported-no-duplicates, which shows the imported data not including
 * duplicates.
 ************************************************************************/

define('cva-de-duplication:views/import/record/detail', ['views/import/record/detail'], function (Dep) {

    return Dep.extend({

        fetchResultPanels: function () {
            var bottomView = this.getView('bottom');

            if (!bottomView) {
                return;
            }

            var importedView = bottomView.getView('imported');

            if (importedView && importedView.collection) {
                importedView.collection.fetch();
            }

            var duplicatesView = bottomView.getView('duplicates');

            if (duplicatesView && duplicatesView.collection) {
                duplicatesView.collection.fetch();
            }

            var importedNoDuplicatesView = bottomView.getView('imported-no-duplicates');

            if (importedNoDuplicatesView && importedNoDuplicatesView.collection) {
                importedNoDuplicatesView.collection.fetch();
            }

            var updatedView = bottomView.getView('updated');

            if (updatedView && updatedView.collection) {
                updatedView.collection.fetch();
            }
        },
    });
});
