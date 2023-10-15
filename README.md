# Sean's Pet Laravel

## Run the server
- Copy env file by executing: `cp .env.example .env`
- Start the docker container in background by running: `docker-composer up -d`
- Permission for log: `chmod 777 storage/logs/laravel.log`
- Permission for session: `chmod -R 777 storage/framework/sessions/*`
- The default server address is: `http://localhost:8081`

## Container Access
You could access the running container by executing this command:
`docker exec -it sean-pet-laravel bash`

## Database Access
In order to access the database, you could use any of the app/script with these credentials:

```
hostname  : localhost
port      : 3307
username  : root
password  : sean-pet
```

## Testing
Please create a database for the testing and copy `.env` to `.env.testing` and adjust it accordingly.
You could run the test by `php artisan test` or if you prefer parallel testing, you could run it by `php artisan test --parallel`.
