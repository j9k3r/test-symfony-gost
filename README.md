# API тестового задания Гост
#### Обновленная версия Symfony 6.4

DEV: http://localhost:80

## Развертывание DEV

### переходим в docker-php8.2

`cd docker-php8.2`

### поднимаем docker

`docker-compose up -d`

### проваливаемся в контейнер пользователя www-data

`docker exec -it --user=www-data app_php-fpm bash `

### устанавливаем симфони и все зависимости

`composer install`

### запускаем миграции

`php bin/console do:mi:mi`

### прописываем токен для сервиса dadata в .env

`DADATA_TOKEN=`

### чистим кэш
`php bin/console ca:cl`

## Спецификация Api

### ### получение организаций (Get):
`localhost:80/api/v1/organization`
принимает параметры
`page` страница и `perPage` количество организаций на странице

пример через url:
`localhost:80/api/v1/organization?page=1&perPage=3`

### получение организации (Get):

`localhost:80/api/v1/organization/{inn}`

пример через url:
`localhost:80/api/v1/organization/7707083893`

#### создание организации (Post)

`localhost:80/api/v1/organization` принимает параметр `inn`