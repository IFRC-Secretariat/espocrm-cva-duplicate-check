# EspoCRM CVA Duplication Check - Extension

This project is an EspoCRM extension for duplicate checking of cash distributions, based on a personal ID.


## About

The software is based on [EspoCRM](https://www.espocrm.com/). Customisations have been made to EspoCRM by adding files to the code to meet the requirements of a CVA duplicate check system. The customisations are described in detail [below](#customisations).


## Setup

### 1. Server setup

Follow the instructions below to set up a remote server to install the system on. If you are installing locally or already have a server set up, skip this step.

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

### 2. EspoCRM setup

The following will install a standard EspoCRM installation. The files are installed at `/var/www/espocrm/`, and logs are at `/var/www/espocrm/data/espocrm/data/logs/`.
To remove a previous installation, run: `sudo rm -r /var/wwww/espocrm/`.

#### HTTP Setup

1. Install EspoCRM based on the [instructions in the documentation](https://docs.espocrm.com/administration/installation-by-script/) for HTTP. 
    
    ```bash
    wget https://github.com/espocrm/espocrm-installer/releases/latest/download/install.sh
    sudo bash install.sh
    ```
    Take note of the username and password for admin access which are printed to the terminal.

2. Verify that you can view and login to the EspoCRM site at http://yourdomain.com.

#### HTTPS Setup with Let's Encrypt

First, link your domain name to your server by configuring the DNS, and ensure that the server is accessible at the domain name.

1. Install EspoCRM based on the [instructions in the documentation](https://docs.espocrm.com/administration/installation-by-script/). 
    ```bash
    wget https://github.com/espocrm/espocrm-installer/releases/latest/download/install.sh
    sudo bash install.sh --ssl --letsencrypt --domain=mydomain.com --email=emailaddress@email.com --clean
    ```
    Note that the `docker-compose.yml` file is saved in `/var/www/espocrm/docker-compose.yml`, and the volumes are in `/var/www/espocrm/data`.
    Take note of the username and password for admin access which are printed to the terminal.

2. Verify that you can view and login to the EspoCRM site at https://yourdomain.com.

4. It is advisable to add a cron job to regularly update the SSL certificate (note that the setup is configured so that the certificates will not update unless they are close to expiring). First, make sure that you can update the certificates by running the following:
    ```bash
    sudo bash -c "/var/www/espocrm/command.sh cert-renew >> /var/www/espocrm/data/letsencrypt/renew.log 2>&1"
    ```
    Verify that the output has been printed to the file:
    ```bash
    sudo cat /var/www/espocrm/data/letsencrypt/renew.log
    ```
    Next, add a cron job to be run as `root`. To add a root cron job, enter `sudo crontab -u root -e`, enter your password, and then copy and paste the following into the editor:
    ```bash
    0 1 * * * /var/www/espocrm/command.sh cert-renew >> /var/www/espocrm/data/letsencrypt/renew.log 2>&1
    ```


### 3. Extension installation

To install this extension on top of the standard EspoCRM installation:

1. Zip the `files`, `scripts`, and `manifest.json` folders and file. You can do this by zipping the files locally, or downloading the zipped files from Github (from the repository page under `Code`, or by downloading a release) and then unzipping, and zipping one level lower. 

2. Login to EspoCRM as an administrator, go to `Administration` -> `Extensions`, upload the zip file, and click the Install button.

3. Run the [front-end-customisations](#front-end-customisations) which affect the user interface and are saved in the database.


### 4. User interface customisations

Customisations need to be made in the user interface (UI) of EspoCRM.


#### Email and 2FA

To enable email sending, go to Administration -> Outbound Emails at `/#Admin/outboundEmails`, and add SMTP details. E.g. if using [Sendgrid](https://sendgrid.com/), the host is `smtp@sendgrid.net`, the username is `apikey`, and the password is the API key.

To set two-factor authentication, go to `Administration` → `Authentication`, and set the following fields:

| Field name | Value | 
| -------- | ------- | 
| Enable 2-Factor Authentication | ✔ | 
| Available 2FA methods | TOTP, Email | 
| Force regular users to set up 2FA | ✔ |


#### User interface

Under `Administration` → `User Interface`, set the following:

| Field name | Field value | Description |
| -------- | ------- | ------- |
| Application Name   | CVA de-duplication system | Or choose a name |
| Company Logo | | Upload a logo |
| Disable User Themes | ✔ | |
| Disable Avatars  | ✔ | |


#### Notifications

Notification settings can be set under `Administration` → `Notifications`.


## Usage

### Users and roles

Users can use the platform to run duplicate checks and upload data on cash distributions. All users should belong to a `Partner`, which should be the organisation name, e.g. WFP, UNICEF, IFRC, etc. To create a partner, click `Partners` in the left menu, click `Create Partner`, and set the `Name` to the organisation name, and set `Roles` to `Partner`. The `Partner` role is created automatically when the extension is installed, and gives users access to:

- Create Cash Distributions and run Duplicate Checks (including importing data)
- View and edit Cash Distributions and Duplicate Checks **of their partner only**
- Delete Cash Distributions **of their partner only**
- View users **of their partner only**

### Uploading cash distributions

It is important to **always** upload data into the platform **as soon as possible** after carrying out a cash distribution. This is so that when other organisations run duplicate checks, they can see who has already received cash. Follow these steps to upload cash distribution data:

1. Upload data: the cash distribution data that you upload should contain a column with National IDs of the person (or head of household) who received the cash. You can also include data on governorate, date of transfer, and transfer value:

    | National ID * (required) | Governorate | Date | Transfer value |
    | -------- | ------- | ------- | ------- |
    | 92846103678 | Homs | 06/11/2023 | 600000 |
    | 23846287365 | Damascus | 30/10/2023 | 800000 |

2. Set properties: enter the property options, including field delimeter, date format (e.g. in the data above this would be `DD/MM/YYYY`), decimal mark, and text qualifier. Check in the preview that the data displays correctly. Click `Next`.

3. Select columns: select the column names to match up to the system column names.

4. Results: the results of the import show:

    - **Imported**: This data was successfully imported and saved in the system.
    - **Duplicates**: This data was saved in the system, but is a duplicate of other cash distribution data. This means that either you or another organisation has already given these people cash.
    - **Errors**: This data was NOT saved in the system because it contains errors. E.g., the National ID is not 11 digits, the date format is incorrect, the transfer value is not a number, etc. Click on each error for more details.

    If needed, you can revert the import by clicking the "Revert Import" button at the top.


### Running duplicate checks

When planning a cash distribution, you can use this system to check whether applicants have already received cash. The comparison is done based on National IDs. Follow these steps to run a duplicate check:

1. Upload data: the cash distribution data that you upload should contain a column with National IDs of the person (or head of household) to duplicate check.

    | National ID * (required) | 
    | -------- |
    | 92846103678 |
    | 83764510394 |

2. Set properties: enter the property options, including field delimeter, and text qualifier. Check in the preview that the data displays correctly. Click Next.

3. Select National ID column: select the National ID column name.

4. Results: the results of the import show:

    - **Data no duplicates**: These people are not in the system so are not recorded as having received cash - you can go ahead and pay them.
    - **Duplicates**: This data is a duplicate of other cash distribution data. This means that either you or another organisation has already given these people cash.
    - **Errors**: This data was NOT duplicate checked because it contains errors. E.g., the National ID is not 11 digits, the date format is incorrect, the transfer value is not a number, etc. Click on each error for more details.

    If needed, you can revert the import by clicking the "Revert Import" button at the top.


## Development

The development workflow should follow:

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

4. Run unit and integration tests: go to `Actions`, then click `Run unit and integration tests` in the left menu. You can see the progress of the tests under `Actions`.

5. If the tests have passed, [create a release](https://docs.github.com/en/repositories/releasing-projects-on-github/managing-releases-in-a-repository).





# Annex

## Docker

The set up is done using Docker. Some helpful Docker information is given in this section.

Generally useful Docker commands:

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


## Customisations

As far as possible, customisations are made using EspoCRM functionality for customisation. The customisations are listed below according to their level of importance to the functionality of the site.

### Level 1: visual customisations

Customisations in this section are visual **only**. They do not affect the performance of the system, but do affect the usability and user experience.

#### Home page customisations

Customisations to the home page have been made based on [similar EspoCRM documentation for customising entity views](https://docs.espocrm.com/development/custom-views/).

Added files:

- `files/client/custom/modules/cva-de-duplication/src/views/home.js`, based on (and to replace) `client/src/views/home.js` with modifications.
- `files/client/custom/modules/cva-de-duplication/res/templates/home.tpl`, based on (and to replace) `client/res/templates/home.tpl` with modifications.
- `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Home.json`, based on (and to replace) `Espo/Resources/metadata/clientDefs/Home.json` with modifications.

#### Import history page

Customised the import list view based on the [EspoCRM documentation](https://docs.espocrm.com/development/custom-views/).

- Set the custom view in `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Import.json`, for list.
- Created file `files/client/custom/modules/cva-de-duplication/src/views/import/list.js`, based on `client/src/views/import/list.js`. Set the header so that the title is “History”.

#### Buttons on Cash Distribution list page

Added a `Import Cash Distribution data` button and a `Check Duplicates` button to the Cash `Distribution` list page, using [EspoCRM customisation functionality for adding buttons](https://docs.espocrm.com/development/custom-buttons/). Buttons: `Import Cash Distribution data` button, and `Check Duplicates` button.

- Modified: `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/CashDistribution.json` - added `menu` key and contents
- Created: `files/client/custom/modules/cva-de-duplication/src/import-cash-distribution-data.js`
- Created: `files/client/custom/modules/cva-de-duplication/src/import-duplicate-check-data.js`

#### Language changes

For all language changes, added new files in: `files/custom/Espo/Modules/CVADeDuplication/Resources/i18n/en_US/`.

As currently only `en_US` is added, if EspoCRM is added in other languages (e.g. Arabic), then a new folder will need to be added in `files/custom/Espo/Modules/CVADeDuplication/Resources/i18n/` with translations into that language.

#### Theme

This extension includes a new theme, `RCRC`, designed in IFRC colours. This can be turned on and off in the EspoCRM front-end, under `Administration` -> `User Interface`. The theme includes the files:

- `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/themes/RCRC.json` - this sets the theme details.
- `files/client/custom/modules/cva-de-duplication/css/rcrc.css` - this includes CSS for the theme.

#### CSS

CSS changes have been made following the [EspoCRM documentation](https://docs.espocrm.com/development/custom-css/), which included adding the files:

- `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/app/client.json`
- `files/client/custom/modules/cva-de-duplication/css/custom.css`


### Level 2: functionality

The customisations below affect the functioning of the system, but are not critical. E.g., if these did not work, the system may be more likely to break.

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

#### “Imported no duplicates” panel on import results page

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

#### Import results page

Customised the import results/ detail view based on the [EspoCRM documentation](https://docs.espocrm.com/development/custom-views/).

- Set the custom view in: `files/custom/Espo/Modules/CVADeDuplication/Resources/metadata/clientDefs/Import.json`, for detail.

- Created file: `files/client/custom/modules/cva-de-duplication/src/views/import/detail.js`, based on `client/src/views/import/detail.js`. Changed the header of the import detail page (removed the link, changed the text). Added a class to the page giving the entity type (`CashDistribution` or `DuplicateCheck`).


### Level 3: critical functionality

The customisations below are integral to the functioning of the system. If these did not work, the system would not function at all for the intended purpose.

#### Custom duplicate checking of DuplicateCheck against CashDistribution

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


#### Setting teams field as teams of creating user

`assignTeam` `beforeSave` [hooks](https://docs.espocrm.com/development/hooks/) are added for `CashDistribution` and `DupilcateCheck` in `custom\Espo\Custom\Hooks\`. These set the `teams` field to be the team of the user creating the entity. This functionality is critical because the `teams` field is used in permissions and visibility - users are only able to see data where the `teams` field of the data contains the team the user is in.

This functionality was added as [hooks](https://docs.espocrm.com/development/hooks/) for the purpose of version control, however it can alternatively be added in the front-end via the formula manager: `Admin → Entity Manager → {Entity} → Formula → Before Save Custom Script` with the following code:

```php
ifThen(
  createdBy.teamsIds,
  entity\addLinkMultipleId('teams', createdBy.teamsIds)
);
```