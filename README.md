# Présentation

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
