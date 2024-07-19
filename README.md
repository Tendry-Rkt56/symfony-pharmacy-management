# Système de Gestion de Pharmacie avec symfony
Ce projet est une adaptation d'un système de gestion de pharmacie existant basé sur PHP, maintenant construit avec symfony. Le système permet à un administrateur de gérer les médicaments, les catégories de médicaments, les utilisateurs et les ventes. Les utilisateurs (caissiers) peuvent consulter les médicaments, les catégories et les autres utilisateurs, éditer leurs profils et sont responsables de l'insertion des achats des clients dans la base de donnée.

## Prérequis
- PHP 8.2.0 ou supérieur
- Composer
- Symfony CLI
- MySQL ou un autre SGBD compatible

## Fonctionnalités

### Admin
- Gérer les médicaments : Ajouter, modifier, supprimer des médicaments.
- Gérer les catégories de médicaments : Ajouter, modifier, supprimer des catégories.
- Gestion des utilisateurs
- Supprimer des ventes dans la base de données.
  
### Utilisateurs
- Consultation des médicaments
- Consultation des catégories de médicaments
- Consultation des autres utilisateurs
- Insérer les médicaments achetés par les clients.
- Modifier leur profil.

## Installation
- Excécution du Fichier SQL :
    Pour initialiser votre base de données avec le schéma et les données nécessaires, exécutez le fichier 'projet.sql' situé à la racine du projet dans votre SGBD	 

- Configurez votre base de données :
    Modifier le fichier `.env` à la racine du projet et configurez les paramètres de connexion à votre base de données.

- Démarrez le serveur de développement :
    Ouvrir le terminal et naviguez vers le répértoire où vous avez placé le projet et taper la commande :
    . symfony server:start (recommandé)
    . php -S localhost:8000 -t public

## Informations de connexion 
  ### Administrateur
      - URL de connexion : 'http://localhost:8000/login'
      - Email : admin01@gmail.com
      - Mot de passe : admin01
  ### Utilisateur
      - URL de connexion : 'http://localhost:8000/login'
      - Email : user01@gmail.com
      - Mot de passe : user01

      - Email : user02@gmail.com
      - Mot de passe : user02


## Contributions
Les contributions sont les bienvenues ! Veuillez ouvrir une issue ou soumettre une pull request pour toute amélioration ou correction de bugs.

## Contact
- **Nom** : Tendry Zéphyrin
- **Email** : tendryzephyrin@gmail.com
- **GitHub** : [Tendry-Rkt56](https://github.com/Tendry-Rkt56)