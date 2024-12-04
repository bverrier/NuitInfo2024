# Présentation

Le fichier Dockerfile permet de construire deux images basées sur l'image "alpine" :
- apache, php8.3 et les extensions requises, et phpMyadmin sont installés et configurés dans l'image nommée "alpine-apache-php8.3" ;
- mariadb est installé et configuré dans l'image nommée "alpine-mariadb".

Le fichier compose.yaml gére 2 conteneurs :
- apache-php_nuitInfo : serveur apache, php et phpMyadmin (exécute l'image "alpine-apache-php8.3")
- mariadb_nuitInfo : serveur mariadb (exécute l'image "alpine-mariadb")

Les applications (scripts PHP) doivent être placés dans le répertoire app.

Les tables de la base de données, et le contenu du répertoire app, sont persistants (grâce à l'utilisation de volumes).
Les modifications du contenu du répertoire app sont donc prises en compte sans nécessité de redémarrer le conteneur apache-php_nuitInfo.

Accès aux applications :
http://localhost:8080

Accès à phpMyadmin :
http://localhost:8080/phpmyadmin
Mot de passe de l'utilisateur root : root_pass

Nom d'hôte du serveur mariadb : mariadb-hostname

# Arborescence
    |
    |__app                  # ajouter vos applications PHP ici
    |   |__best
    |        |__inc
    |           |__controller
    |           |__model
    |           |__service
    |
    |__Dockerfile
    |
    |__compose.yaml


# Commandes

## construction de l'image nommée "alpine-mariadb" (à partir du stage 'mariadb-config' du Dockerfile)

    docker build --tag=alpine-mariadb --target=mariadb-config .


## construction de l'image nommée "alpine-apache-php8.3" (à partir du stage 'apache-php8.3-config' du Dockerfile)

    docker build --tag=alpine-apache-php8.3 --target=apache-php8.3-config .


## démarrage des conteneurs définis dans ./compose.yaml

    docker-compose up -d


## arrêt des conteneurs définis dans ./compose.yaml

    docker-compose kill


## destruction des conteneurs arrêtés (définis dans ./compose.yaml)

    docker-compose rm [-f]


## Listage des volumes

    docker volume ls


## Suppression d'un volume

    docker volume rm <VOLUME NAME>
