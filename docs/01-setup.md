# Setup Instructions

Follow the instructions below to set up EspoCRM with this extension.

## 1. Server setup

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

## 2. EspoCRM setup

The following will install a standard EspoCRM installation. The files are installed at `/var/www/espocrm/`, and logs are at `/var/www/espocrm/data/espocrm/data/logs/`.
To remove a previous installation, run: `sudo rm -r /var/wwww/espocrm/`.

### HTTP Setup

1. Install EspoCRM based on the [instructions in the documentation](https://docs.espocrm.com/administration/installation-by-script/) for HTTP. 
    
    ```bash
    wget https://github.com/espocrm/espocrm-installer/releases/latest/download/install.sh
    sudo bash install.sh
    ```
    Take note of the username and password for admin access which are printed to the terminal.

2. Verify that you can view and login to the EspoCRM site at http://yourdomain.com.

### HTTPS Setup with Let's Encrypt

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


## 3. Extension installation

To install this extension on top of the standard EspoCRM installation:

1. Zip the `files`, `scripts`, and `manifest.json` folders and file. You can do this by zipping the files locally, or downloading the zipped files from Github (from the repository page under `Code`, or by downloading a release) and then unzipping, and zipping one level lower. 

2. Login to EspoCRM as an administrator, go to `Administration` -> `Extensions`, upload the zip file, and click the Install button.

3. Run the [front-end-customisations](#front-end-customisations) which affect the user interface and are saved in the database.


## 4. User interface customisations

Customisations need to be made in the user interface (UI) of EspoCRM.


### Email and 2FA

To enable email sending, go to Administration -> Outbound Emails at `/#Admin/outboundEmails`, and add SMTP details. E.g. if using [Sendgrid](https://sendgrid.com/), the host is `smtp@sendgrid.net`, the username is `apikey`, and the password is the API key.

To set two-factor authentication, go to `Administration` → `Authentication`, and set the following fields:

| Field name | Value | 
| -------- | ------- | 
| Enable 2-Factor Authentication | ✔ | 
| Available 2FA methods | TOTP, Email | 
| Force regular users to set up 2FA | ✔ |


### User interface

Under `Administration` → `User Interface`, set the following:

| Field name | Field value | Description |
| -------- | ------- | ------- |
| Application Name   | CVA de-duplication system | Or choose a name |
| Company Logo | | Upload a logo |


### Notifications

Notification settings can be set under `Administration` → `Notifications`.