# Task Management System

Well hi there! This repository contains the code and scripts for the Fullstack Exercise on Task Management System.

## Setup

**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

**Database Setup**

The code comes with a `docker-compose.yaml` file and we recommend using
Docker to boot a database container. You will still have PHP installed
locally, but you'll connect to a database inside Docker. This is optional,
but I think you'll love it!

First, make sure you have [Docker installed](https://docs.docker.com/get-docker/)
and running. To start the container, run:

```
docker-compose up -d
```

Next, build the database, execute the migrations and load the fixtures with:

```
# "symfony console" is equivalent to "bin/console"
# but its aware of your database container
symfony console doctrine:database:create --if-not-exists
symfony console doctrine:schema:update --force
symfony console doctrine:fixtures:load
```

The `symfony` binary can be downloaded from https://symfony.com/download.

(If you get an error about "MySQL server has gone away", just wait
a few seconds and try again - the container is probably still booting).

If you do *not* want to use Docker, just make sure to start your own
database server and update the `DATABASE_URL` environment variable in
`.env` or `.env.local` before running the commands above.

**Webpack Encore Assets**

This app uses Webpack Encore for the CSS, JS and image files.
To build the Webpack Encore assets, make sure you have
[Yarn](https://yarnpkg.com/lang/en/) installed and then run:

```
yarn install
yarn encore dev --watch
```

**Start the Symfony web server**

You can use Nginx or Apache, but Symfony's local web server
works even better.

To install the Symfony local web server, follow
"Downloading the Symfony client" instructions found
here: https://symfony.com/download - you only need to do this
once on your system.

Then, to start the web server, open a terminal, move into the
project, and run:

```
symfony serve
```

(If this is your first time using this command, you may see an
error that you need to run `symfony server:ca:install` first).

Now check out the site at `https://localhost:8000`


To log in as an admin, use the following login data:

```
username: admin@admin
password: secret
```

To log in as a normal user, use:

```
username: user@user
password: secret
```

Have fun!

### Thanks!