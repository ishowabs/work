# Guide de lancement du projet avec XAMPP

## Prérequis
- XAMPP installé (Apache & MySQL)
- Node.js et npm installés (pour le frontend)
- Composer installé (pour le backend Laravel)

## 1. Configuration du backend (Laravel)

1. Placez le dossier `backend` dans le dossier `htdocs` de XAMPP ou créez un alias Apache pointant vers `backend/public`.
2. Copiez le fichier `.env` fourni ou adaptez-le selon vos besoins (DB_DATABASE, DB_USERNAME, DB_PASSWORD).
3. Démarrez Apache et MySQL via le panneau XAMPP.
4. Dans un terminal, placez-vous dans `backend` et exécutez :
   - `composer install`
   - `php artisan key:generate`
   - `php artisan migrate --seed` (pour créer la base et les tables)
5. Accédez à `http://localhost/backend/public` ou à l’URL configurée dans Apache.

## 2. Configuration du frontend (Nuxt)

1. Placez-vous dans le dossier `frontend`.
2. Installez les dépendances :
   - `npm install`
3. Démarrez le serveur de développement :
   - `npm run dev`
4. Modifiez les appels API du frontend pour pointer vers `http://localhost/backend/public/api` si besoin (voir les fichiers dans `frontend/plugins` ou `frontend/pages`).

## 3. Conseils
- Pour la production, configurez un VirtualHost Apache pointant vers `backend/public`.
- Vérifiez que les ports ne sont pas bloqués par un autre service.
- Pour toute erreur, consultez les logs Apache, Laravel (`storage/logs/`), ou la console du navigateur.

---

**Projet prêt à être lancé avec XAMPP !**
