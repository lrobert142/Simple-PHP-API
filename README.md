Demonstrate a very simple API for users using PHP. I _may_ look into having a demo using both OO and functional programming

# Prerequisites

In order to make most use of this repo, the follow tools are required:
- [Composer](https://getcomposer.org/)
- [PHPUnit 8](https://phpunit.de/getting-started/phpunit-8.html)
    - Requires PHP7.2+. I recommend [PHPBrew](https://github.com/phpbrew/phpbrew) to manage PHP versions

# Features

## Routes

- Create new user
- Get a user (only if authenticated)
- Get multiple users (only if authenticated)
- Update a user (only if authenticated)
- Delete a user (only if authenticated)

## Features

- [x] MySQL & PHP/Apache server using docker-compose
- [x] Dependency Injection
- [x] Separation of concerns via 'layering'
- [ ] Database migrations
- [x] Unit testing with PHPUnit
- [ ] Integration testing of DAOs
- [ ] End-to-End testing

# Running this project

1. Install the required deps using `composer install`
1. Export environment variables for config using `export <ENV_VAR>=<VAL>`
1. Run the Docker containers using `docker-compose up`
1. Wait for the images to be built + readied
1. Use [http://localhost:8000](http://localhost:8000) as the base URL for API calls

## Running Migrations

FIXME: How do we run them? WHEN do we run them?

## Running Tests

1. Simply run `./vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox tests` to run all tests under the `/tests` directory

# Useful Tips

- SSH into PHP/Apache service: `docker exec -it api_server /bin/bash`
- SSH into MySQL service: `docker exec -it api_db /bin/bash`
    - Access DB: `mysql --user root --password` > enter password > `use database;`
