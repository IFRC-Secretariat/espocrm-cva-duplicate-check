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