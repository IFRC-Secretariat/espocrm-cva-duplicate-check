# EspoCRM CVA Duplication Check

This project is a software platform which can be used for duplicate checking of cash distributions, based on a personal ID. The system is based on [EspoCRM](https://www.espocrm.com/), with some customisations.

EspoCRM version: 7.5.4


## Setup

Follow the instructions below (in the order they are written) to set up the system. The setup instructions are using Docker, although it could be set up without this.

### Create Docker containers

First, install EspoCRM version 7.5.4, [using Docker](https://docs.espocrm.com/administration/docker/installation/). Run the following to create a container for the database and a container for the EspoCRM files, changing ```password``` to a strong password:

```bash
docker run --name mysql -e MYSQL_ROOT_PASSWORD=password -d mysql:8 --default-authentication-plugin=caching_sha2_password
docker run --name my-espocrm -e ESPOCRM_SITE_URL=http://172.20.0.100:8080 -e ESPOCRM_DATABASE_PASSWORD=password -p 8080:80 --link mysql:mysql -d espocrm/espocrm:7.5.4
```

The site should now be accessible at ```http://172.20.0.100:8080```. If running locally, ommit ```ESPOCRM_SITE_URL``` from the above command and access the site at ```http://localhost:8080/```.

### Front-end customisations

Customisations need to be made in the front-end of EspoCRM to configure settings which affect the database. 

#### Entity manager

Customise entities using the [Entity Manager](https://docs.espocrm.com/administration/entity-manager/) under ```Administration``` → ```Entity Manager```:

- Create a new ```CashDistribution``` entity with the following details: 

    | Field name    | Field value |
    | -------- | ------- |
    | Name  | CashDistribution |
    | Type | Base     |
    | Label Singular   | Cash Distribution    |
    | Label Plural   | Cash Distributions    |
    | Icon   | far fa-credit-card    |

    Customise the following existing fields:

    | Name | Label | Pattern | Tooltip Text |
    | -------- | ------- | ------- | ------- |
    | name | National ID | ^[0-9]{11}$ | National ID of the individual who received cash (head of household or household member) |
    | teams | Partners | | |

    Create the following custom fields:

    | Name    | Type | Label | Tooltip Text | Min | Decimal Places |
    | -------- | ------- | ------- | ------- | ------- | ------- |
    | date  | Date | Date | Transfer date | | |
    | governorate  | Varchar | Governorate | | | |
    | transferValue | Float | Transfer value | | 2 | 0 |

- Create a new ```DuplicateCheck``` entity with the following details:

    | Field name    | Field value |
    | -------- | ------- |
    | Name  | DuplicateCheck |
    | Type | Base     |
    | Label Singular   | Duplicate Check    |
    | Label Plural   | Duplicate Checks    |
    | Icon   | fas fa-network-wired    |

    Customise the following existing fields:

    | Name | Label | Pattern | Tooltip Text |
    | -------- | ------- | ------- | ------- |
    | name | National ID | ^[0-9]{11}$ | National ID of the individual who received cash (head of household or household member) |
    | teams | Partners | | |

- Add an ```action``` field to the ```Import``` entity by going to the URL: ```/#Admin/fieldManager/scope=Import```:

    | Name    | Type | Label | 
    | -------- | ------- | ------- | 
    | action  | Varchar | Action | 

- Disable all entities except ```CashDistribution```, ```DuplicateCheck```, ```Import```, and ```Users```. Do this by clicking on each entity, clicking ```Edit```, and checking the ```Disabled``` checkbox.

#### Roles

Under ```Administration``` → ```Roles```, create a role with the ```Name``` set to ```Partner```. Set ```Export Permission ``` to ```yes```, and all other permissions to ```no```. Set the following ```Scope Level``` permissions:

|  | Access | Create | Read | Edit | Delete | Stream |
| -------- | ------- | ------- | ------- | ------- | ------- | ------- |
| Activities | <span style="color: rgb(242, 51, 51);">disabled</span> |
| Calendar | <span style="color: rgb(242, 51, 51);">disabled</span> |
| Cash Distributions | <span style="color: #6BC924;">enabled</span> | <span style="color: #6BC924;">yes</span> | <span style="color: #999900;">team</span> | <span style="color: #999900;">team</span> | <span style="color: #999900;">team</span> |
| Currency | <span style="color: rgb(242, 51, 51);">disabled</span> | | <span style="color: rgb(242, 51, 51);">no</span> | <span style="color: rgb(242, 51, 51);">no</span> |
| Duplicate Checks	 | <span style="color: #6BC924;">enabled</span> | <span style="color: #6BC924;">yes</span> | <span style="color: #999900;">team</span> | <span style="color: #999900;">team</span> | <span style="color: rgb(242, 51, 51);">no</span> |
| Email Templates	 | <span style="color: rgb(242, 51, 51);">disabled</span> | <span style="color: rgb(242, 51, 51);">no</span> | <span style="color: rgb(242, 51, 51);">no</span> | <span style="color: rgb(242, 51, 51);">no</span> | <span style="color: rgb(242, 51, 51);">no</span> |
| External Accounts	 | <span style="color: rgb(242, 51, 51);">disabled</span> |
| Import | <span style="color: #6BC924;">enabled</span> |
| Personal Email Accounts	 | <span style="color: rgb(242, 51, 51);">disabled</span> |
| Teams | <span style="color: rgb(242, 51, 51);">disabled</span> | | <span style="color: rgb(242, 51, 51);">no</span> |
| Users | <span style="color: #6BC924;">enabled</span> | | <span style="color: #999900;">team</span> | <span style="color: rgb(242, 51, 51);">no</span> |
| Webhooks | <span style="color: rgb(242, 51, 51);">disabled</span> |
| Working Time Calendars | <span style="color: rgb(242, 51, 51);">disabled</span> |

Set the following ```Field Level``` permissions:

|  |  | Read | Edit |
| -------- | ------- | ------- | ------- |
| Cash Distributions | |
| | Partners | <span style="color: #6BC924;">yes</span> | <span style="color: rgb(242, 51, 51);">no</span> |

#### Optional customisations

We suggest the following customisations, however these are entirely optional as they do not affect the functionality, so they can be set to whatever desired.

- User interface: under ```User Interface```, set the following:

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

- System customiations: under ```Administration``` → ```System```, set ```Global Search Entity List``` to ```CashDistribution, User```.

- Notifications: under ```Administration``` → ```Notifications```, turn off all ```In-app Notifications``` and ```Email Notifications```.

### Create a backup

It is advisable to create a backup of the Docker containers at this point:

```bash
sudo docker commit -p [espocrm-container-id] yyyy-mm-dd-espocrm
sudo docker commit -p [db-container-id] yyyy-mm-dd-mysql
```

These are saved as Docker images and are shown in the list:

```bash
docker images
```

### Fetch customisations from Github repo

First, enter the EspoCRM docker container - you can run ```docker ps``` to show the list of running Docker containers and get the container ID:

```bash
docker ps
docker exec -it [espocrm-container-id] bash
```

Next, fetch the customisations from this Github repo, and pull them into the root of the EspoCRM installation. The ```--hard``` option is required as some EspoCRM files will be overwritten by customised files in this project. 

```bash
git init
git remote add origin https://github.com/AlexxxH/espocrm-cva-duplicate-check.git
git fetch origin
git reset origin/master --hard
```