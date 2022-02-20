# projet framework cir3
Noam Nedelec-Salmon *<noam.nedelec-salmon@isen-ouest.yncrea.fr>*

Alexis Le Floch *<alexis.le-floch@isen-ouest.yncrea.fr>*

# Livrables  -> SECTION À SUPPRIMER 
Vous devez fournir les éléments suivants en livraison de votre projet :
 - l’ensemble des sources (code React, code Symfony et export complet de 
la base de données)
 - une documentation :
   - décrivant le modèle de bdd et les modifications éventuelles réalisées
   - les virtualhost utilisés (fournir le(s) fichiers de virtualhost)
   - les urls utilisées
   - le format d’accès à l’api et le format de retour
 - le code doit être commenté

# versions
Version de Symfony : **4.4**

Serveur de base de données : **MySQL** ou **MariaDB**

# Emplacement des fichiers
## Front
Code React dans /front

## Back et Web Service
Code Symfony dans /back

## Base de données
export MySQL de la base de données dans /bdd


# Modèles de la base de données
## tables
```
+----------------------------+
| Tables_in_projet_framework |
+----------------------------+
| echouage                   |
| espece                     |
| zone                       |
+----------------------------+
```
Table **echouage** :
```
+-----------+------+------+-----+---------+----------------+
| Field     | Type | Null | Key | Default | Extra          |
+-----------+------+------+-----+---------+----------------+
| id        | int  | NO   | PRI | NULL    | auto_increment |
| date      | int  | NO   |     | NULL    |                |
| nombre    | int  | NO   |     | NULL    |                |
| zone_id   | int  | YES  | MUL | NULL    |                |
| espece_id | int  | YES  | MUL | NULL    |                |
+-----------+------+------+-----+---------+----------------+
```
Table **espece** :
```
+--------+-------------+------+-----+---------+----------------+
| Field  | Type        | Null | Key | Default | Extra          |
+--------+-------------+------+-----+---------+----------------+
| id     | int         | NO   | PRI | NULL    | auto_increment |
| espece | varchar(50) | NO   |     | NULL    |                |
+--------+-------------+------+-----+---------+----------------+
```
Table **zone** :
```
+-------+-------------+------+-----+---------+----------------+
| Field | Type        | Null | Key | Default | Extra          |
+-------+-------------+------+-----+---------+----------------+
| id    | int         | NO   | PRI | NULL    | auto_increment |
| zone  | varchar(50) | NO   |     | NULL    |                |
+-------+-------------+------+-----+---------+----------------+
```
## relations
![Entre echouage et espece : relation 1,n. Entre echouage et zone : relation 1,n.](bdd/mcd.png)

# virtualhosts créés




# urls utilisés
## front




## back

### Accueil
 - **/** : accueil du back et menu de recherche
 - **/recherche?zone=`{zone_id}`&espece=`{espece_id}`** : recherche d'une espèce dans une zone sélectionnée
   - zone_id: l'identifiant de la zone, ou 0 pour toutes les zones
   - espece_id : l'identifiant de l'espèce

### Échouages
 - **/echouage** : liste des échouages
 - **/echouage/new** : ajouter un échouage
 - **/echouage/`{id}`** : détails d'un échouage
 - **/echouage/`{id}`/edit** : modifier un échouage

### Espèces
 - **/espece** : liste des espèces
 - **/espece/new** : ajouter une espèce
 - **/espece/`{id}`** : détails d'une espèce
 - **/espece/`{id}`/edit** : modifier une espèce

### Zones
 - **/zone** : liste des zones
 - **/zone/new** : ajouter une zone
 - **/zone/`{id}`** : détails d'une zone
 - **/zone/`{id}`/edit** : modifier une zone

# Format d'utilisation de l'API

## Accueil de l'API
### liste des endpoints
#### utilisation :
**/api**
#### retour :
```json
[
  str,
]
```

## échouages groupés par date

### tous les échouages d'une espèce groupés par date
#### utilisation :
**/api/echouages/espece/`{espece_id}`**
#### retour :
```json
[
  {
    "date": int,
    "zone": str,
    "zone_id": int,
    "nombre": int
  },
]
```
### tous les échouages d'une espèce dans une zone groupés par date
#### utilisation :
**/api/echouages/espece/`{espece_id}`/zone/`{zone_id}`**
#### retour :
```json
[
  {
    "date": int,
    "zone": str,
    "zone_id": int,
    "nombre": int
  },
]
```

## échouages groupés par date et affichés par zone

### tous les échouages d'une espsèce groupés par date et affichés par zone
#### utilisation :
**/api/echouages/espece/`{espece_id}`/zones/date**
#### retour :
```json
{
  "{date}": {
    "{zone_id}": int,
  },
}
```

### tous les échouages d'une espèce dans une zone groupés par date et affichés par zone
#### utilisation :
**/api/echouages/espece/`{espece_id}`/zone/`{zone_id}`/date**
#### retour :
```json
{
  "{date}": {
    "{zone_id}": int
  },
}
```

### les échouages d'une espsèce pour chaque date parmi les dates minimales et maximales groupés par date et affichés par zone
#### utilisation :
**/api/echouages/espece/`{espece_id}`/zones/date/`{min}`/`{max}`** 
#### retour :
```json
{
  "{date}": {
    "{zone_id}": int,
  },
}
```

### les échouages d'une espèce dans une zone pour chaque date parmi les dates minimales et maximales groupés par date et affichés par zone
#### utilisation :
**/api/echouages/espece/`{espece_id}`/zone/`{zone_id}`/date/`{min}`/`{max}`** 
#### retour :
```json
{
  "{date}": {
    "{zone_id}": int
  },
}
```


### Espèces

### liste des espèces
#### utilisation :
**/api/espece**
#### retour :
```json
[
  [
    {
      "id": int,
      "espece": str
    }
  ],
]
```

### détails d'une espèce
#### utilisation :
**/api/espece/`{espece_id}`**
#### retour :
```json
[
  [
    {
      "id": int,
      "espece": str
    }
  ]
]
```

### liste des dates où il y a eu un échouage pour cette espèce
#### utilisation :
**/api/espece/`{espece_id}`/date/**
#### retour :
```json
[
  {
    "date": int
  },
]
```

### date la plus vielle d'un échouage de cette espèce
#### utilisation :
**/api/espece/`{espece_id}`/date/min**
#### retour :
```json
int
```

### date la plus récente d'un échouage de cette espèce
#### utilisation :
**/api/espece/`{espece_id}`/date/max**
#### retour :
```json
int
```

### Zones

### liste des zones
#### utilisation :
**/api/zone**
#### retour :
```json
[
  [
    {
      "id": int,
      "zone": str
    }
  ],
]
```

### détails d'une zone
#### utilisation :
**/api/zone/`{zone_id}`**
#### retour :
```json
[
  [
    {
      "id": int,
      "zone": str
    }
  ]
]
```