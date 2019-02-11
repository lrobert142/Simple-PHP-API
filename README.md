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

## TODO

- [x] Run MySQL via Docker
- [x] Run the PHP/Apache server via Docker
- [x] Get both Docker instances setup using docker-compose
- [x] Setup unit testing with PHPUnit
- [ ] Demonstrate separation of concerns via 'layering'
    - [ ] Authentication layer
    - [ ] Authorization layer (user can only modify/delete themselves)
    - [ ] Command layer
    - [ ] DB layer
- [x] Investigate / Implement dependency injection with PHP

# Running this project

1. Install the required deps using `composer install`
1. Run the Docker containers using `docker-compose up`
1. Wait for the images to be built + readied
1. Visit http://localhost:8000 to see the main page

## Running Migrations

FIXME: How do we run them? WHEN do we run them?

## Running Tests

1. Simply run ` ./vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox tests` to run all tests under the `/tests` directory

# NOTES

- SSH into PHP/Apache service: `docker exec -it php_api_example_www_1 /bin/bash`
- SSH into MySQL service: `docker exec -it php_api_example_db_1 /bin/bash`
    - Access DB: `mysql --user root --password` > enter password > `use database;`
