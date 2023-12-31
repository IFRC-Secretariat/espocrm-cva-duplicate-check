# System management

This contains instructions on cohow to manage the system, including adding translations, upgrading EspoCRM, adding new versions of the extension, and what to do if there is a problem.

## How to add translations in GitHub

To add translations into another language, edit the following files:

- Translations: [files/custom/Espo/Modules/CVADeDuplication/Resources/i18n/](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/tree/main/files/custom/Espo/Modules/CVADeDuplication/Resources/i18n)

There is a folder for each language code, e.g. `ar_AR` is for Arabic. In each language folder, there are files which give translations for different parts of the site. To add the translations, change the value on the right hand side, after the `:` symbols.

1. Open the file to translate in GitHub
2. Click the pencil icon on the top right of the file:

    ![GitHub edit symbol](img/github_pencil.png)

3. Make the changes, by changing the text after the `:` symbol, inside the quotes `" "`. E.g. in `Admin.json` in `ar_AR` for Arabic, you would change this:
    ```json
    {
        "labels": {
            "Import": "Tools",
            "Teams": "Partners"
        }
    }
    ```
    to:
    ```json
    {
        "labels": {
            "Import": "أدوات",
            "Teams": "الشريك"
        }
    }
    ```
4. When you're done, click the green `Commit changes...` button on the top right:

    ![GitHub commit changes button](img/github_commit_changes_button.png)

5. In the pop-up, write a short commit message in the `Commit message` box, and then click the green `Commit changes` button.

    ![GitHub commit changes popup](img/github_commit_changes_popup.png)

### How to translate email templates

Email templates are at:

- User access email template: [files/custom/Espo/Custom/Resources/templates/accessInfo/](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/tree/main/files/custom/Espo/Custom/Resources/templates/accessInfo)
- User portal access email template: [files/custom/Espo/Custom/Resources/templates/accessInfoPortal/](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/tree/main/files/custom/Espo/Custom/Resources/templates/accessInfoPortal)
- Two-factor authentication email template: [files/custom/Espo/Custom/Resources/templates/twoFactorCode/](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/tree/main/files/custom/Espo/Custom/Resources/templates/twoFactorCode)

Open the language code folder, e.g. `ar_AR` for Arabic, or add it if it isn't there. Replace the file content with the translation. 


## How to upgrade EspoCRM

1. First, run the upgrade on the **staging/ testing** site:

    1. Open the **staging/ testing** site.

    2. Go to `Administration` → `Upgrade`. Note the current EspoCRM version - here it is `8.0.3`.

        ![EspoCRM administration upgrade page](img/espocrm_upgrade/espocrm_administration_upgrade_page.png)

    3. Click the [link](https://www.espocrm.com/download/upgrades/) to go to the EspoCRM download page.

        ![EspoCRM download page](img/espocrm_upgrade/espocrm_download_page.png)

    4. Download the right upgrade file. E.g. for this example, the current version is shown as `8.0.3`, so we need to download the `EspoCRM-upgrade-8.0.3-to-8.0.4.zip` file.

    5. Upload the file, click the `Upload` button, and then click the `Run Upgrade` button in the popup. 

        ![EspoCRM administration upgrade popup](img/espocrm_upgrade/espocrm_administration_upgrade_popup.png)

    6. Wait until the `Upgraded successfully` popup is shown:

        ![EspoCRM administration upgrade successful popup](img/espocrm_upgrade/espocrm_administration_upgrade_successful.png)

    7. Run the [manual tests](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/docs/04-testing.md#manual-testing) to make sure everything is working properly.

2. **If the upgrade and tests were successful** on the staging/ testing site, run the same upgrade on the main site:

    1. Open the **main** site.

    2. Go to `Administration` → `Upgrade`. Make sure that the EspoCRM version is the **same as the staging/ testing site**, in our case this is `8.0.3`:

        ![EspoCRM administration upgrade page](img/espocrm_upgrade/espocrm_administration_upgrade_page.png)

    3. Upload the file, click the `Upload` button, and then click the `Run Upgrade` button in the popup. 

        ![EspoCRM administration upgrade popup](img/espocrm_upgrade/espocrm_administration_upgrade_popup.png)

    4. Wait until the `Upgraded successfully` popup is shown:

        ![EspoCRM administration upgrade successful popup](img/espocrm_upgrade/espocrm_administration_upgrade_successful.png)


## How to upload a new version of the extension

1. Run the [automated tests](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/docs/04-testing.md#automated-testing-unit-and-integration-tests) to test the new extension.

2. Zip the `files`, `scripts`, and `manifest.json` folders and file. You can do this by zipping the files locally, or downloading the zipped files from Github (from the repository page under `Code`, or by downloading a release) and then unzipping, and zipping one level lower. 

3. Go to the EspoCRM **staging** site.

    1. Go to `Administration` → `Extensions`. Uninstall the currently installed extension. Upload the zip file, and click the `Install` button.

    4. Run the [manual tests in the site](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/docs/04-testing.md#manual-testing) to test the new extension.

4. Go to the EspoCRM **main** site. 

    1. Put the site into maintenance mode by going to `Administration` → `Settings`, and checking the `Maintenance Mode` box.

    2. Go to `Administration` → `Extensions`. Uninstall the currently installed extension. Upload the zip file, and click the `Install` button.


## What to do if there are issues

If there are significant issues which affect the functionality of the site, put the site in maintenance mode: go to `Administration` → `Settings`, and check the `Maintenance mode` checkbox.