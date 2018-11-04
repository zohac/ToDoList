# Authentification et Autorisation

En matière de sécurité, le framework symfony comporte deux mécanismes:

* L'authentification
* L'autorisation

## 1. L'authentification : Le fichier security.yml

Le fichier `app\config\security.yml` permet de répondre aux questions:

* Comment authenfifier les utilisateurs?
* Comment charger les utilisateurs?
* Comment protèger les mots de passe?

La partie la plus importante du fichier `security.yml` est la clé `firewalls`.

### 1.1. Configurer comment les utilisateurs sont authentifiés

L'application ToDo List utilise le firewalls `main`.
Détails des clés utilisées:

* **anonymous** : Défini si l'on peut-être connecté comme utilisateur ananyme sur l'application.
* **pattern** : Une regex définissant les URL filtrées. Ici toutes les URL sont filtrés.
* **user_checker** : Une classe qui vérifie l'utilisateur avant l'authentification au moment du login.
* **form_login** :
  * **login_path** : Le nom de la route utilisée pour se connecter.
  * **check_path** : Le nom de la route utilisée pour vérifier le couple utilisateur/mot de passe.
  * **always_use_default_target_path** : Si à `true`, les utilisateurs sont toujours redirigés vers le chemin cible par défaut, quelle que soit l'URL précédente qui a été stockée dans la session.
  * **default_target_path** : L'URL par défaut pour la redirection, si aucune route n'est définis dans la session.
* **logout** : Autorise la déconnexion.
* **logout_on_user_chang**e : Si cette option est cochée, Symfony déclenche une déconnexion lorsque l'utilisateur a changé. Ne pas le faire est obsolète, donc cette option doit être définie sur true pour éviter d'obtenir des messages d'obsolescence.

### 1.2. Configurer comment les utilisateurs sont chargés

Ici c'est la clé `providers` qui nous intéresse.

### Encoding the User's Password

## Denying Access, Roles and other Authorization

### Roles

### Securing Controllers and other Code

### Access Control in Templates