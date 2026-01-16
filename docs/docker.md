# Docker

Docker est une plateforme ouverte pour le développement, le déploiement et l'exécution d'applications.

Il permet d'empaqueter et d'exécuter une application dans un environnement faiblement isolé appelé conteneur.
Cette isolation et cette sécurité permettent d'exécuter simultanément plusieurs conteneurs sur un même hôte.

Légers et contenant tout le nécessaire à l'exécution de l'application, les conteneurs vous affranchissent des
dépendances du système hôte.
Vous pouvez partager des conteneurs pendant votre travail, en vous assurant que chaque utilisateur dispose du même
conteneur fonctionnant de la même manière.

## Commandes utiles

- `docker-compose up` : Permet de créer et lancé les conteneurs.
    - L'option `-d` permet de lancer les conteneurs en arrière-plan.


- `docker-compose down` : Permet de stopper et de supprimer les conteneurs et les réseaux.
    - L'option `--rmi all` permet de supprimer les images utilisées par les services.


- `docker system prune --all -f` permet de supprimer tous les conteneurs, les réseaux, les images et les volumes non utilisés.


- `docker exec -it [container_name] [command]` permet d'exécuter une commande dans un conteneur spécifique.
    - Si on utilise comme commande `sh` ou `bash`, ça nous permet de lancer une invite de commande dans le conteneur.


- `docker ps` permet de lister les conteneurs actuellement lancés.
    - L'option `-a` permet de lister tous les conteneurs.

## Liens utiles

- [Documentation `docker-compose up`](https://docs.docker.com/reference/cli/docker/compose/up/)
- [Documentation `docker-compose down`](https://docs.docker.com/reference/cli/docker/compose/down/)
- [Documentation `docker system prune`](https://docs.docker.com/reference/cli/docker/system/prune/)
- [Documentation `docker exec`](https://docs.docker.com/reference/cli/docker/container/exec/)
- [Documentation `docker ps`](https://docs.docker.com/reference/cli/docker/container/ps/)
