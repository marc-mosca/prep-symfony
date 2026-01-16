# Installation du framework Symfony

Ce document explique étape par étape comment créer un projet Symfony.

## Prérequis

Pour pouvoir créer un projet Symfony, vous avez besoin de :

- PHP 8.4 ou supérieur
- Composer

## Installation

Pour créer une application Symfony, vous devez exécuter les commandes suivantes :

```shell
composer create-project symfony/skeleton:"8.0.*" <project-name>
```

Cette commande permet de créer le squelette d'une application Symfony (utilisé pour les micro-services,
les applications en console ou les API).

Si vous souhaitez créer une application web, vous devez rajouter le paquet :

```shell
composer require webapp
```

Vous venez de créer votre application Symfony.

## Liens utiles

- [Installation et configuration du framework Symfony](https://symfony.com/doc/current/setup.html)
