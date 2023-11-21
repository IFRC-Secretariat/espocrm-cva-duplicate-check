# EspoCRM CVA Duplication Check - Extension

## About

This project is an [EspoCRM](https://www.espocrm.com/) extension for duplicate checking of cash distributions, based on a National ID. It is set up for use by multiple partners/ organisations, where each partern/ organisation can only view it's own data, but can run duplicate checking on the whole dataset.

The extension is built for duplicate checking of cash distributions in Syria, based on Syrian National ID. The settings are set up for Syria, including:

- Currency set to Syrian Pounds
- Timezone set to Damascus
- National ID must be 11 digits

However the extension can be adapted to other use cases where comparison can be done based solely on a National ID.


## Documentation

Documentation and instructions are in the following files:

- [Setup instructions](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/docs/01-setup.md) - how to set up the server, install EspoCRM, and install the extension.
- [Usage instructions](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/docs/02-usage.md) - how to use the software, including adding users and partners, uploading cash distribution data, and running duplicate checks.
- [How to](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/docs/03-how-to.md) - how to add translations, upgrade EspoCRM, and install a new version of the extension.
- [Running tests](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/docs/04-testing.md) - how to make edits in GitHub and locally, and how to install new versions of the extension.
- [Developer documentation](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/blob/main/docs/05-developer-documentation.md) - information on all of the files in the extension and what they do.