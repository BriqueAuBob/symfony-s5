# Brandon Clément TP E

## CMS Headless Symfony

### Installation

1. Clone project
2. Run `docker-compose up -d`
3. Run
   # Pour Mac
    ```bash
    docker cp $(docker compose ps -q php):/data/caddy/pki/authorities/local/root.crt /tmp/root.crt && sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain /tmp/root.crt
    ```
   # Pour Linux
    ```bash
    $ docker cp $(docker compose ps -q php):/data/caddy/pki/authorities/local/root.crt /usr/local/share/ca-certificates/root.crt && sudo update-ca-certificates
    ```
   # Pour Windows
    ```bash
    $ docker compose cp php:/data/caddy/pki/authorities/local/root.crt %TEMP%/root.crt && certutil -addstore -f "ROOT" %TEMP%/root.crt
    ```
4. Run `docker exec -ti $(docker-compose ps -q php) /bin/bash`
5. Run `composer install`
6. Run `php bin/console doctrine:migrations:migrate`
7. Go to `https://localhost`

### Documentation
Docs are available at `https://localhost/api/docs`
