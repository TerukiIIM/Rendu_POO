# PokeFight

## Contributeur(s)

> Jérôme ZHAO ([TerukiIIM](https://github.com/TerukiIIM))

## Description

**PokeFight** est une application web qui permet aux utilisateurs de sélectionner, soigner et combattre avec des Pokémon.
L'application utilise l'API [PokeAPI](https://pokeapi.co/) pour récupérer les données des Pokémon et offre une interface utilisateur intuitive pour gérer les combats et les soins des Pokémon.

## Fonctionnalités

### Sélection de Pokémon

-   **Recherche de Pokémon** : Les utilisateurs peuvent rechercher des Pokémon par nom complet.
-   **Affichage des Pokémon** : Les Pokémon sont affichés avec leurs images, noms et types.
-   **Pagination** : Les utilisateurs peuvent naviguer entre les pages de Pokémon.

### Gestion des Attaques

-   **Sélection des Attaques** : Les utilisateurs peuvent sélectionner les attaques pour leurs Pokémon.
-   **Sauvegarde des Attaques** : Les attaques sélectionnées sont sauvegardées dans la session.

### Combat de Pokémon

-   **Début du Combat** : Les utilisateurs peuvent lancer un combat contre un Pokémon adverse.
-   **Attaques** : Les utilisateurs peuvent choisir des attaques pour combattre l'adversaire.
-   **Logs de Combat** : Les actions et résultats des combats sont enregistrés et affichés.
-   **Fin du Combat** : Les résultats du combat sont affichés et les PV restants sont sauvegardés.

### Centre Pokémon

-   **Soins des Pokémon** : Les utilisateurs peuvent soigner leurs Pokémon au Centre Pokémon.
-   **Redirection Automatique** : Si un Pokémon a perdu des PV après un combat, l'utilisateur est redirigé vers le Centre Pokémon.

### Système de Session

-   **Sauvegarde des Données** : Les données des Pokémon et des combats sont sauvegardées dans la session pour une utilisation ultérieure.

---

## Installation

1. **Clonez le dépôt** :
    ```bash
    git clone https://github.com/TerukiIIM/Rendu_POO
    cd Rendu_POO
    ```
2. **Installez les dépendances** :
    ```bash
    composer install
    ```
3. **Configurez votre serveur web** pour pointer vers le répertoire du projet.
4. **Démarrez le serveur** :
    ```bash
    php -S localhost:8000
    ```
5. **Accédez à l'application** dans votre navigateur :
    ```
    http://localhost:8000
    ```

---

## Utilisation

### Sélection de Pokémon

1. Accédez à la page de sélection de Pokémon.
2. Utilisez le champ de recherche pour trouver un Pokémon par nom.
3. Cliquez sur un Pokémon pour le sélectionner.

### Gestion des Attaques

1. Après avoir sélectionné un Pokémon, accédez à la page de sélection des attaques.
2. Choisissez jusqu'à 4 attaques pour votre Pokémon. (min: 1)
3. Cliquez sur "Confirmer" pour sauvegarder les attaques.

### Combat de Pokémon

1. Lancez un combat depuis la page d'accueil.
2. Choisissez des attaques pour combattre l'adversaire.
3. Suivez les logs de combat pour voir les résultats des actions.
4. À la fin du combat, les PV restants de votre Pokémon sont sauvegardés.

### Centre Pokémon

1. Si votre Pokémon a perdu des PV, vous serez redirigé vers le Centre Pokémon.
2. Cliquez sur "Soigner" pour restaurer les PV de votre Pokémon.

---

## Important

### Problème avec les PV en Combat

Il y a un problème connu avec les PV en combat. Si un Pokémon perd des PV mais remporte le combat, au prochain combat, s'il gagne encore, ses PV max seront les PV qu'il lui reste. Cela signifie que les PV max peuvent être incorrectement mis à jour avec les PV restants après un combat.

---

## Prochaines Fonctionnalités

### Système de Login

-   **Inscription et Connexion** : Les utilisateurs pourront créer un compte et se connecter pour sauvegarder leurs données de manière persistante.
-   **Gestion des Profils** : Chaque utilisateur aura un profil avec ses Pokémon et ses statistiques de combat.
-   **Sécurité** : Implémentation de la sécurité pour protéger les données des utilisateurs.

### Améliorations des Combats

-   **IA Améliorée** : Amélioration de l'intelligence artificielle pour des combats plus stratégiques.
-   **Animations de Combat** : Ajout d'animations pour rendre les combats plus dynamiques.

### Interface Utilisateur

-   **Design Réactif** : Amélioration de l'interface utilisateur pour une meilleure expérience sur mobile.
-   **Personnalisation** : Options de personnalisation pour les utilisateurs, comme les thèmes et les avatars.

### Améliorations des Choix

- **Recherche de Pokémon** : Amélioration de la recherche, Ajout de la recherche des Pokémon par nom partiel.
- **Filtres** : Les utilisateurs pourront filtrer les pokemons par types, lettres, generation.
