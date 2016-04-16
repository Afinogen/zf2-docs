Документооборот
===============

Задание
-------
Программное обеспечение для автоматизации электронного документооборота на предприятии.
Функции:
- регистрация документа
- поиск по документам
- индексация, классификация по ключевым словам


Установка
---------
Установить требуемые библиотеки через composer
```php
curl -s https://getcomposer.org/installer | php
php composer.phar install
```
Залить бд из файла data/docs-mysql.sql


Настройка веб-сервера
---------------------

### PHP CLI server

При версии php 5.4 и выше можно запустить php-сервер из корневой директории:

    php -S 0.0.0.0:8080 -t public/ public/index.php

После запуска сайт будет доступен по адресу localhost:8080.

**Note:** Только для разработки.

### Vagrant server

This project supports a basic [Vagrant](http://docs.vagrantup.com/v2/getting-started/index.html) configuration with an inline shell provisioner to run the Skeleton Application in a [VirtualBox](https://www.virtualbox.org/wiki/Downloads).

1. Run vagrant up command

    vagrant up

2. Visit [http://localhost:8085](http://localhost:8085) in your browser

Look in [Vagrantfile](Vagrantfile) for configuration details.

### Apache setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName zf2-app.localhost
        DocumentRoot /path/to/zf2-app/public
        <Directory /path/to/zf2-app/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
            <IfModule mod_authz_core.c>
            Require all granted
            </IfModule>
        </Directory>
    </VirtualHost>

### Nginx setup

To setup nginx, open your `/path/to/nginx/nginx.conf` and add an
[include directive](http://nginx.org/en/docs/ngx_core_module.html#include) below
into `http` block if it does not already exist:

    http {
        # ...
        include sites-enabled/*.conf;
    }


Create a virtual host configuration file for your project under `/path/to/nginx/sites-enabled/zf2-app.localhost.conf`
it should look something like below:

    server {
        listen       80;
        server_name  zf2-app.localhost;
        root         /path/to/zf2-app/public;

        location / {
            index index.php;
            try_files $uri $uri/ @php;
        }

        location @php {
            # Pass the PHP requests to FastCGI server (php-fpm) on 127.0.0.1:9000
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_param  SCRIPT_FILENAME /path/to/zf2-app/public/index.php;
            include fastcgi_params;
        }
    }

Restart the nginx, now you should be ready to go!
