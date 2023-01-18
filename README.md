# Требования

* Требуется [composer](https://getcomposer.org/download/).
* Требуется [Docker](https://docs.docker.com/engine/install/).

# Установка

### Клонирование репозитория
* Клонировать репозиторий `git clone https://github.com/br3ekachu-source/cloud-storage-api.git`;
* Перейти в папку storage `cd cloud-storage-api`;

### Установка 
* Установка компонентов composer `composer install` 
(если версия php ниже 8.0.2, то `docker run --rm -v $(pwd):/app composer/composer:latest install`)

* Копирование файла конфигурации `cat .env.example > .env`;
* Запуск Sail в фоновом режиме `./vendor/bin/sail up -d`;
* Миграция базы данных `./vendor/bin/sail artisan migrate`;
* Если permission denied ошибка, выполнить - 
	`cd ..`
	`chmod 775 cloud-storage-api -R`

* Запуск планировщика `php artisan schedule:work`.

# Postman

* Файл `cloud-storage-api.postman_collection.json`

### Реализованный дополнительный функционал

* Получение размера файлов внутри директории;
* Получение размеров всех файлов на диске;
* Генерация уникальной публичной ссылки на файл;
* Возможность при загрузке указывать срок хранения файла, после которого он сам удаляется;