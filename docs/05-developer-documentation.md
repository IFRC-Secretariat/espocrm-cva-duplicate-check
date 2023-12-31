# Developer Documentation

This section documents the extension files, including the new entities `CashDistribution` and `DuplicateCheck`, and customisations to standard EspoCRM functionality and UI/ UX. The project is an EspoCRM extension, and has the standard [EspoCRM extension structure](https://docs.espocrm.com/development/extension-packages/).


## Making developments

To make developments, follow these steps:

1. Make changes to the files locally. You can set up a local instance to test on and make changes, [as described in the documentation](https://docs.espocrm.com/administration/installation-by-script/):

    ```bash
    wget https://github.com/espocrm/espocrm-installer/releases/latest/download/install.sh
    sudo bash install.sh
    ```
    Make sure that everything works as expected. The following [EspoCRM commands](https://docs.espocrm.com/administration/commands/) are useful if making changes:
    ```bash
    php clear_cache.php # Clear the cache for new changes to take effect
    php rebuild.php # Rebuild to clear the cache and update the database
    ```

2. Update the version in the `manifest.json`.

3. Push changes to GitHub.

4. Run unit and integration tests: go to [Run unit and integration tests](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/actions/workflows/run-tests.yml) in `Actions`. Click the `Run workflow` button. You can see the progress of the tests under [Actions](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/actions).

5. If the tests have passed, optionally [create a release](https://docs.github.com/en/repositories/releasing-projects-on-github/managing-releases-in-a-repository). This will automatically run the tests again.


## Docker

The set up is done using Docker. Some helpful Docker information is given below.

```bash
docker ps # List running Docker containers
docker ps -a # List all Docker containers (running and stopped)
docker images # List images
```

To create a backup of the Docker containers at any time, run:

```bash
sudo docker commit -p [container-name] yyyy-mm-dd-espocrm
```
These are saved as Docker images and are shown in the list: `docker images`.


## New entities: CashDistribution and DuplicateCheck

The extension includes two custom entities: `CashDistribution` and `DuplicateCheck`. `CashDistribution` is data on cash distributions which have been carried out, including the National ID of the person receiving cash, the governorate, the date, the transfer amount, and the partner/ organisation providing the cash. `DuplicateCheck` is an entity used for carrying out duplicate checks, e.g. of applicants to a cash program, where the user can import a list of National IDs which are compared against `CashDistribution` data, and the results are displayed. 

These two custom entities are defined in the following files, which are auto-generated when you create the entities in the EspoCRM user interface:

- `Controllers`: [files/custom/Espo/Modules/CVADeDuplication/Controllers](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Controllers)
- `Entities`: [files/custom/Espo/Modules/CVADeDuplication/Entities](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Entities)
- `Repositories`: [files/custom/Espo/Modules/CVADeDuplication/Repositories](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Repositories)
- `Services`: [files/custom/Espo/Modules/CVADeDuplication/Services](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Services)
- `Resources/metadata/clientDefs` defines entity settings, including buttons on pages: [files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/tree/main/files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs)
- `Resources/metadata/entityDefs` defines fields, links, collection, and indexes: [files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/entityDefs](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/tree/main/files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/entityDefs)
- `Resources/metadata/scopes` defines entity settings. For all entities except `CashDistribution` and `DuplicateCheck`, this sets the entity to diabled: [files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/scopes](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/tree/main/files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/scopes)


## Extension scripts

The following scripts are run when the extension is installed or uninstalled:

- Run when the extension is installed: [scripts/AfterInstall.php](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/scripts/AfterInstall.php)
    - Sets user interface settings, currency settings, and authentication settings
    - Adds a role `Partner`, with correct permissions. This enables users to only view data from their partner/ organisation, but to run duplicate checks against all dta.
- Run when the extension is uninstalled: [scripts/AfterUninstall.php](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/scripts/AfterUninstall.php)
    - Removes the custom entities from the tab list, quick create list, and global search list.


## Customisations

There are also customisations which change the standard EspoCRM functionality and UI/ UX. As far as possible, these have been made following [EspoCRM documentation](https://docs.espocrm.com/development/). The customisations are listed below, from most critical (affecting critical functionality), to least critical (affecting visual appearance and UI/ UX).

- [Level 1: Critical functionality](#level-1-critical-functionality)
- [Level 2: Non-critical functionality](#level-2-non-critical-functionality)
- [Level 3: Visual customisations and UI/ UX](#level-3-visual-customisations-and-ui-ux)


### Level 1: Critical functionality

The customisations below are integral to the functioning of the system. If these did not work, the system would not function at all for the intended purpose.

#### Custom duplicate checking of DuplicateCheck against CashDistribution

Duplicate checking is not set by default for created entities. It has been set up for the new entities ```CashDistribution``` and ```DuplicateCheck``` following the [EspoCRM guidance for custom duplicate checking](https://docs.espocrm.com/development/duplicate-check/). Added files:

- [files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/recordDefs/CashDistribution.json](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/recordDefs/CashDistribution.json)
- [files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/recordDefs/DuplicateCheck.json](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/recordDefs/DuplicateCheck.json)

which contain:

```php
{
    "duplicateWhereBuilderClassName": "Espo\\Classes\\DuplicateWhereBuilders\\Name"
}
```

This uses the existing `DuplicateWhereBuilder` at `Espo/Classes/DuplicateWhereBuilders/Name.php`, as this compares based on the `Name` field which in this case represents the Syrian National ID.

With these changes, duplicate checking will be run, but duplicate checking for an entity compares imported data to other data of that entity. This means that `DuplicateCheck` data is compared to other `DuplicateCheck` data, rather than to `CashDistribution` data. To change this so that `DuplicateCheck` data is compared to `CashDistribution` data, a ```beforeSave``` ```ImportEntity``` [hook](https://docs.espocrm.com/development/hooks/) has been added. This uses `checkIsDuplicate` to run a duplicate check against `CashDistribution` data, and saves the results in the `isDuplicate` property. This uses custom `Service` and `Finder` classes. Added files:

- [files/custom/Espo/Modules/CVADeDuplication/Hooks/ImportEntity/DuplicateCheckCashDistributions.php](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Hooks/ImportEntity/DuplicateCheckCashDistributions.php)
- [files/custom/Espo/Modules/CVADeDuplication/DuplicateCheckEntityType/Serivce.php](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/DuplicateCheckEntityType/Service.php)
- [files/custom/Espo/Modules/CVADeDuplication/DuplicateCheckEntityType/Finder.php](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/DuplicateCheckEntityType/Finder.php)


#### Setting teams field as teams of creating user

[Hooks](https://docs.espocrm.com/development/hooks/) are added to set the `teams` field of created or imported `CashDistribution` or `DuplicateCheck` data to be the team of the user creating the entity. This is critical because the `teams` field is used in permissions - users are only able to see data where the `teams` field contains the team the user is in. Added files:

- [files/custom/Espo/Modules/CVADeDuplication/Hooks/CashDistribution/AssignTeam.php](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Hooks/CashDistribution/AssignTeam.php)
- [files/custom/Espo/Modules/CVADeDuplication/Hooks/DuplicateCheck/AssignTeam.php](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Hooks/DuplicateCheck/AssignTeam.php)

This functionality was added as [hooks](https://docs.espocrm.com/development/hooks/) for the purpose of version control, however it can alternatively be added in the front-end as a [before-save script](https://docs.espocrm.com/administration/api-before-save-script/) via the formula manager: `Admin → Entity Manager → {Entity} → Formula → Before Save Custom Script` with the following code:

```php
ifThen(
  createdBy.teamsIds,
  entity\addLinkMultipleId('teams', createdBy.teamsIds)
);
```


### Level 2: Non-critical functionality

The customisations below affect the functioning of the system, but are not critical. E.g., if these did not work, the system may be more likely to break, or would not meet some of the user requirements.

#### “Imported no duplicates” panel on import results page

It was a requirement to be able to view and download data with no duplicates on the import results page, after running a duplicate check. Therefore, although this is a visual change, it is important to the functionality of the site.

This is added as a new panel on the import detail page. Added files:

- [files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Import.json](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Import.json)
    - Adds another panel to the import detail page, showing imported data without duplicates. Based on `/application/Espo/Resources/metadata/clientDefs/Import.json`.
    - Points to a custom view for the import record details, which includes the new panel: `cva-de-duplication:views/import/record/detail`.
    - Points to a custom view for the new panel: `cva-de-duplication:views/import/record/panels/imported-no-duplicates`.

- [files/client/custom/modules/cva-de-duplication/src/views/import/record/detail.js](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/client/custom/modules/cva-de-duplication/src/views/import/record/detail.js)
    - Custom view for the import detail page; extends `client/src/views/import/record/detail.js`.
    - Adds the new option `imported-no-duplicates`.

- [files/client/custom/modules/cva-de-duplication/src/views/import/record/panels/imported-no-duplicates.js](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/client/custom/modules/cva-de-duplication/src/views/import/record/panels/imported-no-duplicates.js)
    - View for the new panel; based on `client/src/views/import/record/panels/imported.js`.
    - Sets the link to: link: `importedNoDuplicates`.
    - Calls `findLinkedImportedNoDuplicates` in the `Services` file below.

- [files/custom/Espo/Modules/CVADeDuplication/Services/Import.php](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Services/Import.php)
    - Extends `Espo/Services/Import.php`.
    - Based on the `findLinked` method, but calls the new functions `findResultRecordsImportedNoDuplicates` and `countResultRecordsImportedNoDuplicates` in the `Repositories` file below.

- [files/custom/Espo/Modules/CVADeDuplication/Repositories/Import.php](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Repositories/Import.php)
    - Extends `Espo/Repositories/Import.php`.
    - Created new methods based on the methods: `findResultRecords`, `addImportEntityJoin`, `countResultRecords`.


#### Custom currency: Syrian Pounds

The `transferValue` field in `CashDistribution` is a currency field. Syrian Pounds is not a default currency in EspoCRM, so it has been added as a custom currency following the [EspoCRM documentation](https://docs.espocrm.com/administration/currency/#adding-missing-currency). Added file:

- [files/custom/Espo/Custom/Resources/metadata/app/currency.json](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Custom/Resources/metadata/app/currency.json)

Currency settings are set when the extension is installed:

- [scripts/AfterInstall.php](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/scripts/AfterInstall.php)


#### Import validation

A before-save import [hook](https://docs.espocrm.com/development/hooks/) is added at [files/custom/Espo/Modules/CVADeDuplication/Hooks/Import/CheckFieldValues.php](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Hooks/Import/CheckFieldValues.php).
This validates import form data before an import is run to check that:

- The entity type is either `CashDistribution` or `DuplicateCheck`
- The action is `create` (not update)
- Duplicate checking is not set to skip
- All selected fields are custom fields or `name` (not built-in fields)
- No selected fields are read-only fields
- Fields aren't selected multiple times in the field mapping
- Required fields are set in the field mapping

If any of these checks fail, an error message appears on the screen and the user has to fix the issue.


#### Import results page

Customised the import results/ detail view based on the [EspoCRM documentation](https://docs.espocrm.com/development/custom-views/), to change the header (remove the link, change the text), and add a class to the page giving the entity type (`CashDistribution` or `DuplicateCheck`) for CSS. The class is important because it affects the panels and fields which show on the page, depending on whether it is a `CashDistribution` import, or a `DuplicateCheck` import. Added files:

- [files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Import.json](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Import.json)
    - Set the detail view to point to the custom view, `cva-de-duplication:views/import/detail`.
- [files/client/custom/modules/cva-de-duplication/src/views/import/detail.js](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/client/custom/modules/cva-de-duplication/src/views/import/detail.js)
    - Based on `client/src/views/import/detail.js`.


#### User: email address required field

Email address has been set as a required field in:

- [files/custom/Espo/Custom/Resources/metadata/entityDefs/User.json](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Custom/Resources/metadata/entityDefs/User.json)


### Level 3: Visual customisations and UI/ UX

Customisations in this section are visual **only**. They do not affect the performance of the system, but do affect the usability and user experience.


#### Home page customisations

Customisations to the home page have been made based on [EspoCRM documentation for customising entity views](https://docs.espocrm.com/development/custom-views/). This adds buttons to the home page, and sets a custom template with HTML.

Added files:

- [files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Home.json](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Home.json)
    - Sets the custom home page view.
    - Based on (and to replace) `Espo/Resources/metadata/clientDefs/Home.json`.
- [files/client/custom/modules/cva-de-duplication/src/views/home.js](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/client/custom/modules/cva-de-duplication/src/views/home.js)
    - Custom view for the home page, sets the custom template.
    - Based on (and to replace) `client/src/views/home.js`.
- [files/client/custom/modules/cva-de-duplication/res/templates/home.tpl](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/client/custom/modules/cva-de-duplication/res/templates/home.tpl)
    - Custom template for the home page.
    - Based on (and to replace) `client/res/templates/home.tpl`.


#### Import list page (previous cash distributions and duplicate checks)

These customisations modify the import list view, based on the [EspoCRM documentation](https://docs.espocrm.com/development/custom-views/). This is to modify the page header. Added files:

- [files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Import.json](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Import.json)
    - Sets the custom import list view.
- [files/client/custom/modules/cva-de-duplication/src/views/import/list.js](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/client/custom/modules/cva-de-duplication/src/views/import/list.js)
    - Custom import list view. Sets the header to a custom title.
    - Based on `client/src/views/import/list.js`.


#### New import column: action

An extra column, `action`, is added to the import entity, which gives a description of the entity type. This is added to the entity definition, set as a hook, and added to the import list and detail layouts. Added files:

- [files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/entityDefs/Import.json](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/entityDefs/Import.json)
    - Adds the new field `action` to the import entity definition.
- [files/custom/Espo/Modules/CVADeDuplication/Hooks/Import/SetFieldValues.php](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Hooks/Import/SetFieldValues.php)
    - Sets the new `action` field to a description of the import entity when data is imported.
- [files/custom/Espo/Custom/Resources/layouts/Import/list.json](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Custom/Resources/layouts/Import/list.json)
    - Adds the new `action` field to the table on the import list page, with a link to the detail page.
- [files/custom/Espo/Custom/Resources/layouts/Import/detail.json](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Custom/Resources/layouts/Import/detail.json)
    - Adds the new `action` field to the import detail page, with a link to the detail page.


#### Buttons on the CashDistribution list page

Added a `Import data` button and a `Check Duplicates` button to the Cash `Distribution` list page, using [EspoCRM customisation functionality for adding buttons](https://docs.espocrm.com/development/custom-buttons/). Added files:

- [files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/CashDistribution.json](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/CashDistribution.json)
    - Added buttons to the import list page by appending to the list.
- [files/client/custom/modules/cva-de-duplication/src/import-cash-distribution-data.js](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/client/custom/modules/cva-de-duplication/src/import-cash-distribution-data.js)
    - Sends to the import page, with `formData.entityType` set to `CashDistribution`.
- [files/client/custom/modules/cva-de-duplication/src/import-duplicate-check-data.js](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/client/custom/modules/cva-de-duplication/src/import-duplicate-check-data.js)
    - Sends to the import page, with `formData.entityType` set to `DuplicateCheck`.


#### Language changes

For language changes, added files:

- US English: [files/custom/Espo/Modules/CVADeDuplication/Resources/i18n/en_US/](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/tree/main/files/custom/Espo/Modules/CVADeDuplication/Resources/i18n/en_US)
- Arabic: [files/custom/Espo/Modules/CVADeDuplication/Resources/i18n/en_US/](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/tree/main/files/custom/Espo/Modules/CVADeDuplication/Resources/i18n/ar_AR)

To use another language, you should copy the files in one of the folders above into the language code folder, and change the translations to that language.


#### RCRC theme

This extension includes a new theme, `RCRC`, designed in Red Cross/ Red Crescent colours. This can be turned on and off in the EspoCRM front-end, under `Administration` → `User Interface`. Added files:

- Theme details: [files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/themes/RCRC.json](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/tree/main/files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/themes/RCRC.json)
- Theme CSS: [files/client/custom/modules/cva-de-duplication/css/rcrc.css](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/client/custom/modules/cva-de-duplication/css/rcrc.css)


#### Custom CSS

CSS changes have been made following the [EspoCRM documentation](https://docs.espocrm.com/development/custom-css/). Added files:

- Adds the new CSS file: [files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/app/client.json](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/tree/main/files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/app/client.json)
- Custom CSS: [files/client/custom/modules/cva-de-duplication/css/custom.css](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/files/client/custom/modules/cva-de-duplication/css/custom.css)


#### Layouts

Custom layouts for `CashDistribution` and `DuplicateCheck` are added to set which columns should show in the list and detail pages. Added files:

- `CashDistribution` layouts: [files/custom/Espo/Modules/CVADeDuplication/Resources/layouts/CashDistribution](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/tree/main/files/custom/Espo/Modules/CVADeDuplication/Resources/layouts/CashDistribution)
- `DuplicateCheck` layouts: [files/custom/Espo/Modules/CVADeDuplication/Resources/layouts/DuplicateCheck](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/tree/main/files/custom/Espo/Modules/CVADeDuplication/Resources/layouts/DuplicateCheck)


#### Email templates

By default, email templates for resetting password and user access details, include "EspoCRM". Custom templates have been added to remove this:

- [files/custom/Espo/Custom/Resources/templates](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/tree/main/files/custom/Espo/Custom/Resources/templates)