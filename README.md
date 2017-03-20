# CyberGestionnaire V1.9

## Présentation

CyberGestionnaire est un logiciel servant à gérer un espace multimédia. Il fonctionne en PHP et permet la gestion de clients avec le logiciel historique EPNConnect, ou le plus récent mais moins abouti [LGBConnect] (https://github.com/ctariel/LGB-Connect "https://github.com/ctariel/LGB-Connect").

A la base, c'est un fork de [Cybermin] (http://cybermin.free.fr/index.php?m=1 "http://cybermin.free.fr/index.php?m=1"), repris par [maxletesteur] (https://maxletesteur.jimdo.com/cyber-gestionnaire/ "https://maxletesteur.jimdo.com/cyber-gestionnaire/").

Vous pourrez trouver plus d'aide sur le forum suivant : http://animepn.openphpbb.com/forum

## nouvelle installation

Merci de consulter la documentation originale pour l'installation :https://maxletesteur.jimdo.com/cyber-gestionnaire/installation-et-param%C3%A9trages/

## Mise à jour d'un CyberGestionnaire existant

### Etapes préliminaires

Avant de mettre à jour votre CyberGestionnaire, il faut s'assurer de 2 choses :

1. Sauvegarder le fichier connect_db.php : il sera nécessaire de le remettre pour accéder de nouveau à votre base
2. Vérifier que le répertoire "sql" existe et à bien les droits en écriture pour le serveur apache

### Procédure de mise à jour

1. sauvegarder le fichier "connect_db.php"
2. écraser les fichiers présents dans le répertoire d'installation
3. remettre le fichier "connect_db.php" précedemment sauvegardé
4. se connecter au gestionnaire
5. suivre les étapes de mise à jour :
     * sauvegarde de la base (pensez à vérifier qu'elle a bien fonctionnée avant d'aller plus loin !!)
     * mise à jour de la base de données
