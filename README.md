mv .env.example .env
composer install
php artisan migrate
docker-compose up --build

**Endpoints**
POST
```
curl --location 'http://127.0.0.1:8080/api/jobs/' \
--header 'X-API-KEY: NjxBA5cMs29' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data '{
    "urls": [
        "https://lrytas.lt"
    ],
    "selectors": [
        "h3",
        "p"
    ]
}'
```
GET
```
curl --location 'http://127.0.0.1:8080/api/jobs/674b690e20b481.16889418' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--header 'x-api-key: NjxBA5cMs29' \
--data ''
```
DELETE
```
curl --location --request DELETE 'http://127.0.0.1:8080/api/jobs/674b650bcc9939.25905144' \
--header 'x-api-key: NjxBA5cMs29' \
--header 'Content-Type: application/json' \
--header 'Accept: application/json'
```


