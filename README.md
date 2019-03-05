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

Migrations placed inside the `/migrations` folder will be automatically added when the Docker image is rebuilt and then run whenever the image is run.

If a migration does not seem to be running, make certain it has been included as part of the rebuild step. If it has not, rerun that step using `docker-compose build --no-cache` which will cause the file to be added.

## Running Tests

1. Simply run `./vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox tests` to run all tests under the `/tests` directory
