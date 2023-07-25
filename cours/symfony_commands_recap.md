
### 1. Recap des commandes "Serve"


- Lancement du serveur :
```
symfony serve
```


- Stopper le serveur (au cas où le processus se soit mal fermé) :
```
symfony server:stop
```

### 2. Recap des commandes "maker"


- Création d'entités :
```
symfony console make:entity
```


- Création de contrôleur :
```
symfony console make:controller
```


- Création de filtre/fonction Twig :
```
symfony console make:twig-extension
```


- Création de CRUD :
```
symfony console make:crud
```


- Création d'un formulaire :
```
symfony console make:form
```


- Création des migrations :
```
symfony console make:migration
```


- Création d'un User pour l'authentification :
```
symfony console make:user
```


- Création d'un système d'authentification (nécessite un make:user avant) :
```
symfony console make:auth
```

### 3. Recap des commandes "utilitaires"


- Voir les routes de l'application :
```
symfony console debug:router
```


- Exécuter les migrations :
```
symfony console d:m:m
```


- Exécuter les fixtures (Alice) :
```
symfony console hautelook:fixtures:load --purge-with-truncate -n
```
