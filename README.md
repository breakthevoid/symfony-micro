# symfony-micro
Мини-фреймворк на основе Symfony MicroKernelTrait

### Установка:

#### 1. Установить Symfony CLI (по необходимости):

curl -sS https://get.symfony.com/cli/installer | bash

Источник: https://symfony.com/download

Экспортировать путь в файл конфигурации оболочки командной строки (.bash, .zsh, ..)

export PATH="$HOME/.symfony/bin:$PATH"


#### 2.  Установить необходимые компоненты Symfony

//composer require symfony/framework-bundle symfony/runtime
composer update



#### 3. Запустить локальный веб-сервер Symfony

symfony server:start
