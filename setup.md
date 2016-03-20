SET UP
========

## Install Composer
1. [Download Composer](https://getcomposer.org/)
2. Place composer.phar in Source folder

## Install all vendors
1. Open cmd window and place yourself in Source/Euro2016 directory
```console
php ../composer.phar install
```

## Set up database
1. Create a data base in PhpMyAdmin "euro2016"
2. Copy file Source/Euro2016/app/config/parameters.yml.dist
3. Paste it in the same directory as parameters.yml (drop the .dist)
4. Fill the missing parameters (secret, app ids & app secrets)
5. Open cmd window and place yourself in Source/Euro2016 directory
```console
php app/console doctrine:database:create
```

## Create Admin user
1. Open cmd window and place yourself in Source/Euro2016 directory
```console
php app/console fos:user:create --super-admin
```

## See all open routes
1. Open cmd window and place yourself in Source/Euro2016 directory
```console
php app/console debug:router
```
