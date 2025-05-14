# JobStep - Plateforme d'accompagnement pour la reconversion professionnelle

## lien GITHUB:
https://github.com/MargYre/EVALsymfony

## Installation

1. Cloner le repository
2. Installer les dépendances : `composer install`
3. Configurer la base de données dans `.env`
4. Créer la base de données : `php bin/console doctrine:database:create`
5. Exécuter les migrations : `php bin/console doctrine:migrations:migrate`
6. Lancer le serveur : `symfony server:start`

## Identifiants de connexion

**Mot de passe pour tous les comptes : `test1234`**

| Email | Rôle | Description |
|-------|------|-------------|
| plop@doe.fr | Candidat | Premier compte candidat créé |
| test@jobstep.com | Candidat | Compte candidat avec parcours associé |
| prof@test.com | Conseiller | Compte conseiller (peut créer/modifier des ressources) |

## Fonctionnalités implémentées

✅ **Page de login + enregistrement**
- Login : `/login`
- Inscription : `/register`

✅ **CRUD Message** 
- Liste : `/message`
- Création : `/message/new`
- Modification : `/message/{id}/edit`
- Suppression : `/message/{id}/delete`

✅ **CRUD Ressource**
- Liste : `/ressource`
- Création : `/ressource/new` (conseillers uniquement)
- Modification : `/ressource/{id}/edit` (conseillers uniquement)
- Suppression : `/ressource/{id}/delete` (conseillers uniquement)

✅ **Page des étapes du parcours**
- Vue du parcours : `/parcours/{id}`
- Téléchargement des ressources associées
- Upload de rendus d'activité
- Barre de progression

## Structure de la base de données

Entités créées : User, Parcours, Etape, Ressource, Message, Rendu

Toutes les relations du MLD ont été implémentées conformément au sujet.

## Technologies

- Symfony 7
- PHP 8
- MySQL 8
- Bootstrap 5
- Doctrine ORM

---

**Évaluation Symfony 7 - 14/05/2025**  
Margaux - L3 Pro


# QCM - Bilan des tâches réalisées
Entités créées (ordre conseillé)

✓ User
✓ Ressource
✓ Message
✓ Parcours
✓ Etape
✓ Rendu

Sécurité

✓ Authentification
✓ Enregistrement
✓ Seul un conseiller peut créer un parcours, une étape, une ressource
✗ Seul un conseiller peut se déclarer accompagnant d'un candidat

CRUD

✓ Ressource
✓ Message
✗ Parcours
✗ Etape
✗ Rendu

Dépôt d'un message

✓ L'émetteur d'un message est le user connecté

Tableau de bord d'un parcours

✓ Un parcours doit présenter ses étapes
✓ Une étape doit présenter ses ressources associées avec un lien pour les télécharger
✓ Une étape doit présenter la dernière version du rendu du user connecté associé à cette étape
✓ Un mini formulaire doit permettre le dépôt d'un rendu
✗ Si un message est associé à un rendu, un lien vers ce message est proposé
✓ Le parcours affiché est un parcours associé au user connecté