# Brandon Clément TP E

## CMS Headless Symfony

### Description
This project is a CMS Headless made with Symfony 7.1. It uses the API Platform bundle to create a REST API. The project is dockerized with Caddy, PHP 8.3, and PostgreSQL.

### Features
- Authentication with tokens
- Create, read, update, and delete users
- Create, read, update, and delete content
- Create, read, update, and delete comments
- Create, read, update, and delete tags
- Upload images
- Import content from a CSV file

-----
### Requirements
- Docker
- Docker Compose

### Installation

1. Clone project
2. Run `docker-compose up -d`
3. Run
   #### For Mac
    ```bash
    docker cp $(docker compose ps -q php):/data/caddy/pki/authorities/local/root.crt /tmp/root.crt && sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain /tmp/root.crt
    ```
   #### For Linux
    ```bash
    $ docker cp $(docker compose ps -q php):/data/caddy/pki/authorities/local/root.crt /usr/local/share/ca-certificates/root.crt && sudo update-ca-certificates
    ```
   #### For Windows
    ```bash
    $ docker compose cp php:/data/caddy/pki/authorities/local/root.crt %TEMP%/root.crt && certutil -addstore -f "ROOT" %TEMP%/root.crt
    ```
4. Run `docker exec -ti $(docker-compose ps -q php) /bin/bash`
5. Run `composer install`
6. Run `php bin/console doctrine:migrations:migrate`
7. Go to [https://localhost](https://localhost)

### Documentation
Documentation is available [here](https://localhost/api/docs)

-----
### Useful commands
#### Docker
- `docker-compose up -d` to start the project
- `docker-compose down` to stop the project
- `docker-compose exec $(docker-compose ps -q php) /bin/bash` to access the php container
- `docker-compose exec $(docker-compose ps -q database) /bin/bash` to access the db container
#### Symfony
- `php bin/console app:security:create-user {email} {password}` to create a user
- `php bin/console app:security:grant-user {email}` to changes roles of a user
- `php bin/console app:content:delete` to delete all content

-----
### Credits
- [Brandon Clément](https://bclement.fr)
