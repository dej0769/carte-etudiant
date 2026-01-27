# [Système de Gestion des Cartes Étudiants Numériques]

Ce projet a été réalisé dans le cadre du cours de Programmation Web et Framework (Université Joseph Ki-Zerbo).

## 📌 Sujet du projet
[Développement d'une application web sous **Laravel** pour la gestion des cartes d'étudiants avec génération de **QR Code**. L'application permet à un administrateur de gérer les informations des étudiants et de contrôler la validité de leurs cartes en temps réel.]

## 👥 Membres du groupe
- [DOAMBA EULALIE JULIE]
- [NDO JEAN ARNAUD]

## 🚀 Fonctionnalités principales
- **Authentification** : Espace sécurisé pour l'administrateur.
- **Gestion Étudiants** : Ajout, modification et suppression des profils (avec photo).
- **Cartes Numériques** : Génération automatique d'un QR Code unique par étudiant.
- **Contrôle d'accès** : Possibilité de suspendre ou d'activer une carte instantanément.
- **Vérification** : Page publique affichant les infos de l'étudiant après scan du QR Code.

## 🛠️ Installation
### Prérequis
- PHP 8.1 ou supérieur
- Composer
- MySQL/PostgreSQL
- Node.js & NPM
### Étapes d'installation

Pour installer le projet localement, suivez ces étapes :

1. **Clonez le dépôt :** 
   ```bash
   git clone https://github.com/dej0769/carte-etudiant.git
   ```
2. **Installez les dépendances PHP :**
   ```bash
   composer install
   ```
3. **Installer les dépendances CSS/JavaScript:**
     ```bash
    npm install
    npm run dev
   ```

4. **Configurer l'environnement :**
 DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carte_etudiant
DB_USERNAME=root
DB_PASSWORD=


5. **Lancez les migrations :**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
6. **Lancez le serveur :**
   ```bash
   php artisan serve

   ```
   L'application sera accessible à l'adresse : http://localhost:8000

7.**📝 Routes principales:**
    / : Page d'accueil
    /register : Inscription

    /login : Connexion

    /dashboard : Tableau de bord

    /admin/* : Zone administrateur

    /admin/students : liste des etudiants

    /admin/students/ajouter: nouvel etudiant

    /admin/students/modifier{id}: modifier etudiant

    /admin/students/supprimer{id}: supprimer etudiant

/students/carte/{id}:generation de carte

/gestion-cartes: gestions des cartes

/cards/activate/{student}: activation  la carte

/cards/{id}/suspend: susprendre  la carte

/cards/{id}/expire: expire la carte

/cards/{id}/reactivate: reactiver la carte

/carte/{numero}: carte










