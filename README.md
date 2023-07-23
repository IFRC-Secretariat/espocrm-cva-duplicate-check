# EspoCRM CVA Duplication Check

This project is to add customisations to [EspoCRM](https://www.espocrm.com/), for duplicate checking for cash distributions.

EspoCRM version: 7.5.4


## Setup

1. Setup EspoCRM, version 7.5.4, e.g. [using Docker](https://docs.espocrm.com/administration/docker/installation/).

2. Using the front-end EspoCRM [Entity Manager](https://docs.espocrm.com/administration/entity-manager/), create a ```CashDistribution``` entity with the following fields: 

    ![CashDistribution fields](img/CashDistribution%20fields.png)

3. Using the front-end EspoCRM [Entity Manager](https://docs.espocrm.com/administration/entity-manager/), create a ```DuplicateCheck``` entity with the following fields: 

    ![DuplicateCheck fields](img/DuplicateCheck%20fields.png)

4. Fetch the custom code from Github into the root of the EspoCRM installation. Some EspoCRM files will be overwritten by customised files in this project. 

    ```bash
    git init
    git remote add origin -m master [github-repo-url]
    git pull --force
    ```