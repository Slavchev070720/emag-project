# eMag-project
- `18.02.2019 - 03.03.2019` - This is a final project of ITtalents Season X training camp. The assignment was to create a website similar to eMag for 14 days using MVC design patter, OOP structure, PHP programming language, XAAMP and then present it to their partner companies.
- `01.04.2019 - 14.04.2019` - Refactor on boarding task by UptetiX/Scalefocus. The assignment was to refactor the project with company standards: more complex MVC and OOP structures, PSR standards using company devops template for creating an environment with Vagrant and Ansible.
- `19.10.2020 - 23.10.2020` - The project was return to me without git history and the devops template after I quit the company. I made a new repository and used docker compose to run the project.
- Note: If you want to add products, you can log in as admin with email:`admin@emag.bg` and password:`admin` then click `Account` which is found at top right and then click `Add Product` which is found in the left-side navigation bar.

## Used Technologies
- PHP 7.3-fpm
- PDO
- Nginx 1.18
- MySQL 5.7
- Bootstrap 3.3
- Composer 1.10
- Git 2.25
- Docker 19.03
- Docker-compose 1.27

## Setup Prerequisites
You must have the following tools installed:
- Git - https://git-scm.com/downloads
- Docker - https://docs.docker.com/install/linux/docker-ce/ubuntu/
- Docker Compose - https://docs.docker.com/compose/install/
- You must add the proper virtual host record to your /etc/hosts file: `127.0.0.1	emag-project`
  
## Setup Configuration
- Configuration is in .env(will be created for you based on .env-dist) and there you can tweak database config and some Docker params.
- In case your uid and gid are not 1000 but say 1001, you must change the USER_ID and GROUP_ID vars in .env file. Type the `id` command in your terminal in order to find out.
- When created, your containers' names will be prefixed with COMPOSE_PROJECT_NAME env var, e.g. `emag`. You can change this as per your preference.
- Nginx logs are accessible in ./volumes/nginx/logs
- MySQL data is persisted via a Docker volume.
- Composer cache is persisted via a Docker volume.
- You can write code by loading your project in your favourite IDE, but in order to use Composer you must work in the PHP container.

## Start the Docker ecosystem for a first time
- `mkdir emag-project` - create a new project dir
- `cd emag-project` - get into it
- `git clone https://github.com/Slavchev070720/emag-project.git .` - clone code from repo
- `cp .env-dist .env` - create the .env file
- Now you would want to run `id` command and set USER_ID and GROUP_ID env vars in .env file as per your needs.
- `docker-compose build` - build Docker images and volumes
- `docker-compose run --rm php-dev composer install` - install Composer packages
- `docker-compose up -d` - start the whole ecosystem (wait few seconds for mysql service to start)
- `docker-compose ps` - verify all containers are up and running
- Open your favorite browser and go to `http://emag-project` to see eMag-project homepage.

### Useful commands
- `docker-compose exec php-dev /bin/bash` - enter the php container.
- `docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' container` - gets container's IP
- `docker kill -s HUP container` - can be used to reload Nginx configuration dynamically
