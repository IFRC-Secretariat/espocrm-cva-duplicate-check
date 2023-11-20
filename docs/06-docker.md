# Docker

The set up is done using Docker. Some helpful Docker information is given below.

## Generally useful Docker commands

```bash
docker ps # List running Docker containers
docker ps -a # List all Docker containers (running and stopped)
docker images # List images
```

## Backups

To create a backup of the Docker containers at any time, run:

```bash
sudo docker commit -p [container-name] yyyy-mm-dd-espocrm
```
These are saved as Docker images and are shown in the list: `docker images`.
