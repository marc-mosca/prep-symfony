# Installation d'outils tiers

L'installation d'outils tiers permet de faciliter le développement. Nous allons donc voir comment installer `Webpack`,
`TailwindCSS` et `DaisyUI`.

## Prérequis

- Node (npm)
- Composer

## Webpack Encore

Lors d'une nouvelle installation d'un projet Symfony, le framework fournis par défaut `AssetMapper`.
Il va falloir supprimer `AssertMapper` afin de pouvoir faire une installation propre de `Webpack Encore`.

Pour ce faire, exécuter les commandes ci-dessous :

```shell
# Suppression de AssetMapper et suppression (temporaire) de turbo et stimulus
composer remove symfony/ux-turbo symfony/asset-mapper symfony/stimulus-bundle

# Installation de Webpack Encore et réinstallation de turbo et stimulus
composer require symfony/webpack-encore-bundle symfony/ux-turbo symfony/stimulus-bundle
```

Il ne vous reste plus qu'à installer les dépendances nodes :

```shell
npm install --force
```

## TailwindCSS

Maintenant que `Webpack Encore` est installé, nous allons pouvoir installer `TailwindCSS`.

Pour ce faire, exécuter la commande suivante :

```shell
npm install tailwindcss @tailwindcss/postcss postcss postcss-loader
```

Une fois installé, il va falloir modifier la configuration de `Webpack Encore` en modifiant le fichier
`webpack.config.js` et y rajouter l'option suivante :

```js
Encore
    .enablePostCssLoader()
;
```

Il faudra également configurer le plugin `PostCSS`, pour se faire créer un fichier `postcss.config.mjs` a la racine du
projet et ajouter le plugin `@tailwindcss/postcss` a la configuration :

```js
export default {
  plugins: {
    "@tailwindcss/postcss": {},
  },
};
```

Il ne vous reste plus qu'à importer `TailwindCSS` dans votre feuille de style :

```css
@import "tailwindcss";
@source not "../../public";
```

Pour compiler vos assets, vous pouvez lancer la commande `npm run build` ou lancé le mode watch :

```shell
npm run watch
```

## DaisyUI

Pour installer `DaisyUI`, il vous suffit de lancer la commande :

```shell
npm install -D daisyui@latest
```

Il ne vous reste plus qu'à ajouter le plugin dans votre feuille de style :

```css
@plugin "daisyui";
```

## Liens utiles

- [Documentation de Webpack Encore](https://symfony.com/doc/current/frontend/encore/index.html)
- [Documentation d'outil front-end Symfony](https://symfony.com/doc/current/frontend.html)
- [Installation TailwindCSS avec Symfony](https://tailwindcss.com/docs/installation/framework-guides/symfony)
- [Installation DaisyUI](https://daisyui.com/docs/install/)
