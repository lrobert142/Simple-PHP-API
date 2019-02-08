# Goals

Demonstrate a very simple API for users using PHP. I _may_ look into having a demo using both OO and functional programming

## Routes

- Create new user
- Get a user (only if authenticated)
- Get multiple users (only if authenticated)
- Update a user (only if authenticated)
- Delete a user (only if authenticated)

## TODO

- [ ] Run MySQL via Docker
- [ ] Run the PHP/Apache server via Docker
- [ ] Get both Docker instances setup using docker-compose
- [ ] Demonstrate separation of concerns via 'layering'
    - [ ] Authentication layer
    - [ ] Authorization layer (pass-through unless we look at roles?)
    - [ ] Command layer
    - [ ] DB layer
- [ ] Investigate / Implement dependency injection with PHP
