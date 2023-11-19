# Customisations

As far as possible, customisations are made using EspoCRM functionality for customisation. The customisations are listed below according to their level of importance to the functionality of the site.

## Level 1: Critical functionality

The customisations below are integral to the functioning of the system. If these did not work, the system would not function at all for the intended purpose.

### Custom duplicate checking of DuplicateCheck against CashDistribution

Duplicate checking is not set by default for created entities. It has been set up for the new entities ```CashDistribution``` and ```DuplicateCheck``` following the (EspoCRM guidance for custom duplicate checking)[https://docs.espocrm.com/development/duplicate-check/], which included adding the files:

- `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/recordDefs/CashDistribution.json`
- `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/recordDefs/DuplicateCheck.json`

Rather than using a custom `DuplicateWhereBuilder` as described in the documentation, we have used an existing one at `Espo/Classes/DuplicateWhereBuilders/Name.php`, as this compares based on the `Name` field which is what is needed.

```php
{
    "duplicateWhereBuilderClassName": "Espo\\Classes\\DuplicateWhereBuilders\\Name"
}
```

A ```beforeSave``` ```ImportEntity``` [hook](https://docs.espocrm.com/development/hooks/) has been added, to run a duplicate check of the `DuplicateCheck` entity against `CashDistribution` data using `checkIsDuplicate`, and save the result in the `isDuplicate` property. `Service` and `Finder` classes are set in `custom\Espo\Custom\DuplicateCheckEntityType` following the structure of the usual EspoCRM duplicate check.

Files added:

- `custom\Espo\Custom\Hooks\ImportEntity\DuplicateCheckCashDistributions`
- `custom\Espo\Custom\DuplicateCheckEntityType\Serivce`
- `custom\Espo\Custom\DuplicateCheckEntityType\Finder`


### Setting teams field as teams of creating user

`assignTeam` `beforeSave` [hooks](https://docs.espocrm.com/development/hooks/) are added for `CashDistribution` and `DupilcateCheck` in `custom\Espo\Custom\Hooks\`. These set the `teams` field to be the team of the user creating the entity. This functionality is critical because the `teams` field is used in permissions and visibility - users are only able to see data where the `teams` field of the data contains the team the user is in.

This functionality was added as [hooks](https://docs.espocrm.com/development/hooks/) for the purpose of version control, however it can alternatively be added in the front-end via the formula manager: `Admin → Entity Manager → {Entity} → Formula → Before Save Custom Script` with the following code:

```php
ifThen(
  createdBy.teamsIds,
  entity\addLinkMultipleId('teams', createdBy.teamsIds)
);
```


## Level 2: Non-critical functionality

The customisations below affect the functioning of the system, but are not critical. E.g., if these did not work, the system may be more likely to break, or would not meet some of the user requirements.

### “Imported no duplicates” panel on import results page

It was a requirement to be able to view and download data with no duplicates on the import results page, after running a duplicate check. Therefore, although this is a visual change, it is important to the functionality of the site.

File changes:

- Created file: `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Import.json`
    - Based on file: `/application/Espo/Resources/metadata/clientDefs/Import.json`, but adding another panel using `APPEND`
    - Pointing to the view `custom:views/import/record/panels/imported-no-duplicates`, and `custom:views/import/record/detail`
- Created file: `files/client/custom/modules/cva-de-duplication/src/views/import/record/panels/imported-no-duplicates.js`
    - Based on file `client/src/views/import/record/panels/imported.js`, but setting the link to: link: `importedNoDuplicates`
- Created new file: `files/custom/Espo/Modules/CVADeDuplication/Services/Import.php`
    - Extending the file `Espo/Services/Import.php`
    - Based on the `findLinked` method, but calling the new functions `findResultRecordsImportedNoDuplicates` and `countResultRecordsImportedNoDuplicates`
- Created new file: `files/custom/Espo/Modules/CVADeDuplication/Repositories/Import.php`
    - Extending the file: `Espo/Repositories/Import.php`
    - Created new methods specific to based on the methods: `findResultRecords`, `addImportEntityJoin`, `countResultRecords`
- Created new file: `files/client/custom/modules/cva-de-duplication/src/views/import/record/detail.js`, based on `client/src/views/import/record/detail.js`
    - Added the new option `imported-no-duplicates`


### Import validation

The `beforeSave` `Import` [hooks](https://docs.espocrm.com/development/hooks/) are run before an `Import` entity is saved:

- `custom\Espo\Custom\Hooks\Import\CheckFieldValues` runs validation checks on the import:
    - The entity type is either `CashDistribution` or `DuplicateCheck`
    - The action is `create` (not update)
    - Duplicate checking is not set to skip
    - All selected fields are custom fields or `name` (not built-in fields)
    - No selected fields are read-only fields
    - Fields aren't selected multiple times in the field mapping
    - Required fields are set in the field mapping

- `custom\Espo\Custom\Hooks\Import\SetFieldValues` sets the value of the field `action` to display on the import list page.


### Import results page

Customised the import results/ detail view based on the [EspoCRM documentation](https://docs.espocrm.com/development/custom-views/).

- Set the custom view in: `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Import.json`, for detail.

- Created file: `files/client/custom/modules/cva-de-duplication/src/views/import/detail.js`, based on `client/src/views/import/detail.js`. Changed the header of the import detail page (removed the link, changed the text). Added a class to the page giving the entity type (`CashDistribution` or `DuplicateCheck`).


## Level 3: Visual customisations and UI/ UX

Customisations in this section are visual **only**. They do not affect the performance of the system, but do affect the usability and user experience.


### Home page customisations

Customisations to the home page have been made based on [similar EspoCRM documentation for customising entity views](https://docs.espocrm.com/development/custom-views/).

Added files:

- `files/client/custom/modules/cva-de-duplication/src/views/home.js`, based on (and to replace) `client/src/views/home.js` with modifications.
- `files/client/custom/modules/cva-de-duplication/res/templates/home.tpl`, based on (and to replace) `client/res/templates/home.tpl` with modifications.
- `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Home.json`, based on (and to replace) `Espo/Resources/metadata/clientDefs/Home.json` with modifications.


### Import history page

Customised the import list view based on the [EspoCRM documentation](https://docs.espocrm.com/development/custom-views/).

- Set the custom view in `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Import.json`, for list.
- Created file `files/client/custom/modules/cva-de-duplication/src/views/import/list.js`, based on `client/src/views/import/list.js`. Set the header so that the title is “History”.


### Buttons on Cash Distribution list page

Added a `Import Cash Distribution data` button and a `Check Duplicates` button to the Cash `Distribution` list page, using [EspoCRM customisation functionality for adding buttons](https://docs.espocrm.com/development/custom-buttons/). Buttons: `Import Cash Distribution data` button, and `Check Duplicates` button.

- Modified: `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/CashDistribution.json` - added `menu` key and contents
- Created: `files/client/custom/modules/cva-de-duplication/src/import-cash-distribution-data.js`
- Created: `files/client/custom/modules/cva-de-duplication/src/import-duplicate-check-data.js`


### Language changes

For all language changes, added new files in: `files/custom/Espo/Modules/CVADeDuplication/Resources/i18n/en_US/`.

As currently only `en_US` is added, if EspoCRM is added in other languages (e.g. Arabic), then a new folder will need to be added in `files/custom/Espo/Modules/CVADeDuplication/Resources/i18n/` with translations into that language.


### Theme

This extension includes a new theme, `RCRC`, designed in IFRC colours. This can be turned on and off in the EspoCRM front-end, under `Administration` -> `User Interface`. The theme includes the files:

- `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/themes/RCRC.json` - this sets the theme details.
- `files/client/custom/modules/cva-de-duplication/css/rcrc.css` - this includes CSS for the theme.


### CSS

CSS changes have been made following the [EspoCRM documentation](https://docs.espocrm.com/development/custom-css/), which included adding the files:

- `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/app/client.json`
- `files/client/custom/modules/cva-de-duplication/css/custom.css`