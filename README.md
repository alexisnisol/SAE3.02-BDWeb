# SAE3.02-BDWeb
Github : https://github.com/alexisnisol/SAE3.02-BDWeb

## Membres de l'équipe
- Mouad Zouadi  
- Alexis Nisol  
- Alexy Wiciak  

---

## Introduction
Ce projet a été réalisé en équipe de 3 dans le cadre de la SAE Web/BD sur un club de Poneys. Il met en œuvre plusieurs fonctionnalités que nous avons apprise en cours et appliquées lors de ce projet.

---

## Prérequis
Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :
- PHP (version 8.3 ou supérieure)
- Serveur web local (Apache, Nginx ou équivalent)
- Base de données MySQL/MariaDB/SQLite

---

## Fonctionnalités

### Utilisateurs

En tant qu'utilisateur, vous devez créer un compte pour vous connecter afin de vous inscrire aux cours disponibles sur le planning dans la page "Réservations".

Vous pouvez à tout moment modifier vos données personnelles sur la partie droite du planning dans votre profil.

Pour réserver un cours, il faut payer l'inscription annuelle et les cours pourlesquels vous voulez vous inscrire.

Vous avez aussi la possibilité de voir les informations de chaque cours pour voir s'ils vous correspondent (le niveau, l'horaire, la date, le moniteur, le thème, le poney, la liste des participants...)

---

### Admin

En tant qu'administrateur,vous pouvez vous connecter avec les identifiants :  
> **nom** : `admin@cavalier.com`  
> **mot de passe** : `adm`

En plus d'avoir accès à toutes les fonctionnalités des utilisateurs, vous pourrez aussi créer des cours programmés (avec toutes ces caracteristiques), et les cours réalisés afin de les rendre disponible et visible sur le planning des utilisateurs.

Vous pouvez aussi créer des poneys et voir leur planning dédié, ainsi que le planning des moniteurs.

Enfin, l'administrateur pourra supprimer les moniteurs qu'il souhaite.

---

## Modifications depuis la soutenance
- Paiement
- Planning Poney
- Planning Moniteur
- Liste participant d'un cours
- Utilisateur administrateur qui ajoute/retire des moniteurs
- Bug dimanche
- Modifier info personne

## Installation

**Cloner le dépôt** :
   ```bash
   git clone https://github.com/alexisnisol/SAE3.02-BDWeb.git
   cd src
   php -S localhost:8000
   ```

