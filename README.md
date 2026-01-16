# Préparation Symfony

Ce projet est une application Symfony permettant de découvrir le framework et les outils associés.

Le projet tourne sous Docker avec l'application sous Symfony 8 et avec une base de données sous MySQL.

## Prérequis

- PHP 8.4
- Composer
- Docker
- NodeJS (npm)

## Installation

Pour installer le projet localement sur votre machine, vous devez cloner le dépôt :

```shell
git clone https://github.com/marc-mosca/prep-symfony
cd prep-symfony
```

Ensuite, vous devez installer les différentes dépendances du projet :

```shell
composer install
npm install
```

Ensuite, vous devez définir vos variables d'environnement :

```shell
cp .env .env.local
```

*Modifier toutes les valeurs nécessaires au fonctionnement du projet.*

Ensuite, vous devez créer la base de données et les tables associées :

```shell
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

Ensuite, vous devez compiler les assets :

```shell
npm run build
```

Pour finir, vous pouvez lancer l'application (grace à Docker) :

```shell
docker-compose up
```

Vous pouvez désormais accéder a l'application en allant sur https://localhost:8000.
