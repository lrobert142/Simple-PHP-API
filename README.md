Demonstrate a very simple API for users using PHP.

# Prerequisites

In order to make most use of this repo, the follow tools are required:
- [Composer](https://getcomposer.org/)
- [PHPUnit 8](https://phpunit.de/getting-started/phpunit-8.html)
    - Requires PHP7.2+. I recommend [PHPBrew](https://github.com/phpbrew/phpbrew) to manage PHP versions

# Features

- [x] MySQL & PHP/Apache server using docker-compose
- [x] Dependency Injection
- [x] Separation of concerns via 'layering'
- [x] Unit testing with PHPUnit

# Running this project

1. Install the required deps using `composer install`
1. Export environment variables for config using `export <ENV_VAR>=<VAL>`
    1. I would recommend [direnv](https://direnv.net/) for managing project environment variables
1. Run the Docker containers using `docker-compose up`
1. Wait for the images to be built + readied
1. Run DB migrations (see below)
1. Use [http://localhost:8000](http://localhost:8000) as the base URL for API calls

## Running Migrations

Migrations must be run manually on the database, but should not require any servers to be restarted.

To run migrations:

1. Make sure the DB container is running
1. SSH into the container via: `docker exec -it api_db /bin/bash`
1. Set the DB to use via: `mysql --user <DB_USER> --password` > enter password > `use <DB_NAME>;`
1. Copy and paste the migrations in order, executing one after the other.
1. The database should now be migrated!

**Note:** I am aware that this is not the best way to approach DB migrations, but for simplicity this will suffice for now

## Running Tests

1. Simply run `./vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox tests` to run all tests under the `/tests` directory
