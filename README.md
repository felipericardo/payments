# Payments

### Steps to run

```sh
$ cp .env.example .env
$ docker-compose up -d
$ docker exec -it php sh -c "composer install"
$ docker exec -it php sh -c "php artisan migrate --seed"
```

### Documentation
https://documenter.getpostman.com/view/326459/2sA35D53Tg

![payments.drawio.png](doc%2Fpayments.drawio.png)
