/************************************************************************
 * Added to extend export handling to relationships in Import results/ detail page, 
 * 
 * Extending file "client/src/handlers/import.js"
 *
 ************************************************************************/

define('cva-de-duplication:handlers/import', ['handlers/import'], function (Dep) {

    return Dep.extend({

	actionExportImportRelationship: function (data, e) {
	    let postPath = `Import/${this.view.model.id}/exportRelationship/${this.view.link}`;
	    Espo.Ajax
                .postRequest(postPath)
                .then(data => {
                    if (!data.attachmentId) {
                        let message = this.view.translate('noRecords', 'messages', 'Import');

                        Espo.Ui.warning(message);

                        return;
                    }

                    window.location = this.view.getBasePath() + '?entryPoint=download&id=' + data.attachmentId;
                })
		.catch(xhr => {
			xhr.errorIsHandled = true;
			let message = this.view.translate('exportRequestFailed', 'messages', 'Import');
                        Espo.Ui.error(message+`${xhr.status} ${xhr.statusText}`);
		});
        }

    });
});
