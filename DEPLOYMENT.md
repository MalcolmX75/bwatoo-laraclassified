# Déploiement LaraClassified sur IONOS

## Informations Serveur

### Serveur de développement
- **URL**: 212.227.78.104
- **Répertoire**: /var/www/vhosts/bwatoo.fr/DEMOS/dev
- **Utilisateur SFTP**: bwatoo.fr_micky
- **Mot de passe**: 68_02M@et@
- **Clé SSH**: ~/.ssh/bwatoo_rsa

### Accès
```bash
# SSH
ssh -i ~/.ssh/bwatoo_rsa bwatoo.fr_micky@212.227.78.104

# SFTP
sftp -i ~/.ssh/bwatoo_rsa bwatoo.fr_micky@212.227.78.104
```

## État du déploiement

### Étapes réalisées (04/07/2025)
1. ✅ Configuration de la connexion SSH avec clé
2. ✅ Nettoyage du répertoire /var/www/vhosts/bwatoo.fr/DEMOS/dev
3. ✅ Transfert du fichier source laraclassified-geo-classified-ads-cms.zip
4. ✅ Décompression des fichiers sur le serveur

### Prochaines étapes
1. Installation manuelle selon la documentation (/documentation/index.html)
2. Configuration de la base de données
3. Configuration du fichier .env
4. Installation des dépendances (composer install)
5. Génération de la clé d'application
6. Migration de la base de données
7. Configuration des permissions

## Installation manuelle

Suivre les instructions dans la documentation :
- /Users/xdream/projets/baoprod/bwatoo-laraclassified/documentation/index.html

## Notes
- Le serveur utilise PHP et MySQL/MariaDB
- Les fichiers sont maintenant décompressés dans /var/www/vhosts/bwatoo.fr/DEMOS/dev
- L'URL publique sera : https://bwatoo.fr/DEMOS/dev (après configuration)