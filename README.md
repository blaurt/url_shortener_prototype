# Url shortener 
https://proglib.io/p/symfony-url-shortener/

## To  run this project make following command in terminal:

**Step 1.**

``
composer install
``

**Step 2.**

``
cp .env.test .env
``

**Step 3.**

Change configurations for your connection in .env file.

**Step 4.**

~~~
php bin/console doctrine:database:create

php bin/console make:migration

php bin/console doctrine:migrations:migrate
~~~

**Step 5.**

~~~
php bin/console server:start
 