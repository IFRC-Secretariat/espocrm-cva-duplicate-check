# EspoCRM CVA Duplication Check - Extension

This project is an EspoCRM extension for duplicate checking of cash distributions, based on a personal ID.


## About

The software is based on [EspoCRM](https://www.espocrm.com/). Customisations have been made to EspoCRM by adding files to the code to meet the requirements of a CVA duplicate check system. The customisations are described in detail [below](#customisations). As far as possible, these customisations have been set up using EspoCRM recommended processes for customisations, e.g. using [hooks](https://docs.espocrm.com/development/hooks/).


## Setup

### Local setup

To set up EspoCRM locally, follow the instructions below.

1. Install EspoCRM by following the instructions in the [documentation](https://docs.espocrm.com/administration/installation-by-script/):

    ```bash
    wget https://github.com/espocrm/espocrm-installer/releases/latest/download/install.sh
    sudo bash install.sh
    ```

2. Download a release of this extension from the Github repository as a zip file. 

3. Login to EspoCRM as an administrator, go to Administration -> Extensions, upload the zip file, and click the Install button.

4. Run the [front-end-customisations](#front-end-customisations) which affect the user interface and are saved in the database.

Logs can be accessed at `/var/www/espocrm/data/espocrm/data/logs/`. The files are installed at `/var/www/espocrm/`. To remove a previous installation, run: `rm -r /var/wwww/espocrm/`.


### Server setup

To set up EspoCRM on a server, follow the instructions below.

1. Set up a remote server with SSH access, e.g. with [DigitalOcean](https://www.digitalocean.com/) or another provider, or self-hosted.

2. Install Docker if not already installed.

3. Create a user and add them to the ```sudo``` and ```docker``` groups:
    ```bash
    adduser myuser
    usermod -aG sudo myuser
    usermod -aG docker myuser
    ```

4. Enable SSH access by copying the key from ```root```:
    ```bash
    rsync --archive --chown=myuser:myuser ~/.ssh /home/myuser
    ```

5. After verifying that you can SSH in as the new user, disable root access:
    ```bash
    sudo vim /etc/ssh/sshd_config   # Set PermitRootLogin to no
    sudo systemctl restart sshd    # Restart the daemon
    ```

6. Set up a firewall:
    ```bash
    ufw allow OpenSSH
    ufw enable
    ```

7. Create a folder to install into:
    ```bash
    mkdir espocrm
    cd espocrm
    ```

8. Install EspoCRM by following the instructions in the [documentation](https://docs.espocrm.com/administration/installation-by-script/):

    ```bash
    wget https://github.com/espocrm/espocrm-installer/releases/latest/download/install.sh
    sudo bash install.sh -y --ssl --letsencrypt --domain=my-espocrm.com --email=email@my-domain.com
    ```

9. Add the following to crontab to set up auto renewal of the SSL certificate, changing `myuser` to the username of the user.
    ```bash
    0 1 * * * /home/myuser/espocrm/command.sh cert-renew    
    ```

10. Download a release of this extension from the Github repository as a zip file. 

11. Login to EspoCRM as an administrator, go to Administration -> Extensions, upload the zip file, and click the Install button.

12. Run the [front-end-customisations](#front-end-customisations) which affect the user interface and are saved in the database.

Logs can be accessed at `/var/www/espocrm/data/espocrm/data/logs/`. The files are installed at `/var/www/espocrm/`. To remove a previous installation, run: `rm -r /var/wwww/espocrm/`.


### Front-end customisations

Customisations need to be made in the front-end of EspoCRM to configure settings which affect the database.

#### Roles

Under ```Administration``` → ```Roles```, create a role with the ```Name``` set to ```Partner```. Set ```Export Permission ``` to ```yes```, and all other permissions to ```no```. Set the following ```Scope Level``` permissions:

|  | Access | Create | Read | Edit | Delete | Stream |
| -------- | ------- | ------- | ------- | ------- | ------- | ------- |
| Activities | <span style="color: rgb(242, 51, 51);">disabled</span> |
| Calendar | <span style="color: rgb(242, 51, 51);">disabled</span> |
| Cash Distributions | <span style="color: #6BC924;">enabled</span> | <span style="color: #6BC924;">yes</span> | <span style="color: #999900;">team</span> | <span style="color: #999900;">team</span> | <span style="color: #999900;">team</span> |
| Currency | <span style="color: rgb(242, 51, 51);">disabled</span> | | | |
| Duplicate Checks	 | <span style="color: #6BC924;">enabled</span> | <span style="color: #6BC924;">yes</span> | <span style="color: #999900;">team</span> | <span style="color: #999900;">team</span> | <span style="color: rgb(242, 51, 51);">no</span> |
| Email Templates | <span style="color: rgb(242, 51, 51);">disabled</span> | | | | |
| External Accounts	 | <span style="color: rgb(242, 51, 51);">disabled</span> |
| Personal Email Accounts	 | <span style="color: rgb(242, 51, 51);">disabled</span> |
| Teams | <span style="color: rgb(242, 51, 51);">disabled</span> | | |
| Tools | <span style="color: #6BC924;">enabled</span> |
| Users | <span style="color: #6BC924;">enabled</span> | | <span style="color: #999900;">team</span> | <span style="color: rgb(242, 51, 51);">no</span> |
| Webhooks | <span style="color: rgb(242, 51, 51);">disabled</span> |
| Working Time Calendars | <span style="color: rgb(242, 51, 51);">disabled</span> |

Set the following ```Field Level``` permissions:

|  |  | Read | Edit |
| -------- | ------- | ------- | ------- |
| Cash Distributions | |
| | Partners | <span style="color: #6BC924;">yes</span> | <span style="color: rgb(242, 51, 51);">no</span> |

#### Visual customisations

We suggest the following customisations which affect the display (but do not affect funcionality):

- User interface: under ```Administration``` → ```User Interface```, set the following:

    | Field name | Field value | Description |
    | -------- | ------- | ------- |
    | Company Logo   |  | Select a logo |
    | Application Name   | SARC CVA de-duplication system | |
    | Theme   | Hazyblue | |
    | Disable User Themes | True | |
    | Disable Avatars  | True | |
    | Records Per Page  | 100 | |
    | Records Per Page (Small) | 10 | |
    | Tab List | Cash Distributions, Import, Users, Teams | |
    | Quick Create List | | (Empty) |
    | Dashboard Layout | | (Empty) |

- System customiations: under ```Administration``` → ```Settings```, set ```Global Search Entity List``` to ```CashDistribution, User```.

- Notifications: under ```Administration``` → ```Notifications```, turn off all ```In-app Notifications``` and ```Email Notifications```.


## Docker

The set up is done using Docker. Some helpful Docker information is given in this section.

### General commands

```bash
docker ps # List running Docker containers
docker ps -a # List all Docker containers (running and stopped)
docker images # List images
```

### Backups

To create a backup of the Docker containers at any time, run:

```bash
sudo docker commit -p [container-name] yyyy-mm-dd-espocrm
```
These are saved as Docker images and are shown in the list: `docker images`.



## Customisations

As far as possible, customisations are made using EspoCRM functionality for customisation.

### CSS

CSS changes have been made following the [EspoCRM documentation](https://docs.espocrm.com/development/custom-css/), which included adding the files:

- `custom/Espo/Custom/Resources/metadata/app/client.json`
- `client/custom/css/custom.css`

### Language changes

For all language changes, added new files in: `custom/Espo/Custom/Resources/i18n/en_US/`.

As currently only `en_US` is added, if EspoCRM is added in other languages (e.g. Arabic), then a new folder will need to be added in `custom/Espo/Custom/Resources/i18n/` with translations into that language.

### Custom duplicate checking of DuplicateCheck against CashDistribution

Duplicate checking is not set by default for created entities. It has been set up for the new entities ```CashDistribution``` and ```DuplicateCheck``` following the (EspoCRM guidance for custom duplicate checking)[https://docs.espocrm.com/development/duplicate-check/], which included adding the files:

- `custom/Espo/Custom/Resources/metadata/recordDefs/CashDistribution.json`
- `custom/Espo/Custom/Resources/metadata/recordDefs/DuplicateCheck.json`

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


#### Import validation

The `beforeSave` `Import` [hooks](https://docs.espocrm.com/development/hooks/) are run before an `Import` entity is saved:

- `custom\Espo\Custom\Hooks\Import\CheckFieldValues` runs validation checks on the import:
    - The entity type is either `CashDistribution` or `DuplicateCheck`
    - The action is `create` (not update)
    - Duplicate checking is not set to skip
    - All selected fields are custom fields or `name` (not built-in fields)
    - No selected fields are read-only fields
    - Fields aren't selected multiple times in the field mapping
    - Required fields are set in the field mapping

- `custom\Espo\Custom\Hooks\Import\SetFieldValues` sets the value of the field `action` so that only import is allowed.


### Setting teams field as teams of creating user

`assignTeam` `beforeSave` [hooks](https://docs.espocrm.com/development/hooks/) are added for `CashDistribution` and `DupilcateCheck` in `custom\Espo\Custom\Hooks\`. These set the `teams` field to be the team of the user creating the entity. This functionality is important because the `teams` field is used in permissions and visibility - users are only able to see data where the `teams` field of the data contains the team the user is in.

This functionality was added as [hooks](https://docs.espocrm.com/development/hooks/) for the purpose of version control, however it can alternatively be added in the front-end via the formula manager: `Admin → Entity Manager → {Entity} → Formula → Before Save Custom Script` with the following code:

```php
ifThen(
  createdBy.teamsIds,
  entity\addLinkMultipleId('teams', createdBy.teamsIds)
);
```

### Buttons on Cash Distribution list page

Added a `Import Cash Distribution data` button and a `Check Duplicates` button to the Cash `Distribution` list page, using [EspoCRM customisation functionality for adding buttons](https://docs.espocrm.com/development/custom-buttons/). Buttons: `Import Cash Distribution data` button, and `Check Duplicates` button.

- Modified: `custom/Espo/Custom/Resources/metadata/clientDefs/CashDistribution.json` - added `menu` key and contents
- Created: `client/custom/src/import-cash-distribution-data.js`
- Created: `client/custom/src/import-duplicate-check-data.js`


### Home page customisations

Customisations to the home page have been made based on [similar EspoCRM documentation for customising entity views](https://docs.espocrm.com/development/custom-views/).

Added files:

- `client/custom/src/views/home.js`, based on (and to replace) `client/src/views/home.js` with modifications.
- `client/custom/res/templates/home.tpl`, based on (and to replace) `client/res/templates/home.tpl` with modifications.
- `Custom/Resources/metadata/clientDefs/Home.json`, based on (and to replace) `Espo/Resources/metadata/clientDefs/Home.json` with modifications.


### “Imported no duplicates” panel on import results page

It was a requirement to be able to view and download data with no duplicates on the import results page, after running a duplicate check.

File changes:

- Created file: `custom/Espo/Custom/Resources/metadata/clientDefs/Import.json`
    - Based on file: `/application/Espo/Resources/metadata/clientDefs/Import.json`, but adding another panel using `APPEND`
    - Pointing to the view `custom:views/import/record/panels/imported-no-duplicates`, and `custom:views/import/record/detail`
- Created file: `/client/custom/src/views/import/record/panels/imported-no-duplicates.js`
    - Based on file `client/src/views/import/record/panels/imported.js`, but setting the link to: link: `importedNoDuplicates`
- Created new file: `Espo/Custom/Services/Import.php`
    - Extending the file `Espo/Services/Import.php`
    - Based on the `findLinked` method, but calling the new functions `findResultRecordsImportedNoDuplicates` and `countResultRecordsImportedNoDuplicates`
- Created new file: `Espo/Custom/Repositories/Import.php`
    - Extending the file: `Espo/Repositories/Import.php`
    - Created new methods specific to based on the methods: `findResultRecords`, `addImportEntityJoin`, `countResultRecords`
- Created new file: `client/custom/src/views/import/record/detail.js`, based on `client/src/views/import/record/detail.js`
    - Added the new option `imported-no-duplicates`


### Import page import selection

On the import page, I wanted to change the labels for selecting an entity to import, to be more descriptive and user friendly. To do this it was necessary to make a **hard-coded** (non-functional):

- Changed line 200 of `client/src/views/import/step1.js` from: `translation: Global.scopeNamesPlural`, to: `translation: Import.selectOptions`.

- Added a new item `selectOptions` in `custom/Espo/Custom/Resources\i18n\en_US\Import.json` for the new translation. 

This change is not functional. It was too complicated to do this in an update secure way, it would have meant writing several files (`controllers/import.js`, `views/index.js`, `views/step1.js`).


### Import results page

Customised the import results/ detail view based on the [EspoCRM documentation](https://docs.espocrm.com/development/custom-views/).

- Set the custom view in: `custom/Espo/Custom/Resources/metadata/clientDefs/Import.json`, for detail.

- Created file: `client/custom/src/views/import/detail.js`, based on `client/src/views/import/detail.js`. Changed the header of the import detail page (removed the link, changed the text). Added a class to the page giving the entity type (`CashDistribution` or `DuplicateCheck`).


### History page

Customised the import list view based on the [EspoCRM documentation](https://docs.espocrm.com/development/custom-views/).

- Set the custom view in `custom/Espo/Custom/Resources/metadata/clientDefs/Import.json`, for list.
- Created file `client/custom/src/views/import/list.js`, based on `client/src/views/import/list.js`. Set the header so that the title is “History”.


### Import data validation - EspoCRM BUG, fix in EspoCRM 8.0.0

Bug found found where invalid import data is converted to default, blank, or unexpected values in parsing. This parsing is done before data validation so cannot be fixed by implementing [custom validation](https://docs.espocrm.com/development/custom-field-type/#backend-validator). Issue raised and fixed on [Github](https://github.com/espocrm/espocrm/issues/2801). The fix is due to be included in version 8.0.0, ETA end of August.

The issue is a bug and could potentially create erranous data, so I have implemented the fixes directly in the code by copying the fixes which were made in two EspoCRM commits [here](https://github.com/espocrm/espocrm/commit/0c26d35287d0f8ee53bd4ed502266c523c4e70cd, and [here](https://github.com/espocrm/espocrm/commit/753daebadf0336137134b2702ff196f294f30325). Upgrading to 8.0.0 will overwrite these changes but should include the fix.

This affects the following files:

- `application/Espo/Resources/i18n/en_US/Global.json`
- `application/Espo/Tools/Import/Import.php`
- `application/Espo/Classes/FieldValidators/IntType.php`
- `application/Espo/Resources/metadata/fields/float.json`
- `application/Espo/Resources/metadata/fields/int.json`