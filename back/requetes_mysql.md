# requêtes MySQL

Recherche selon la zone et l'espece :
```sql
SELECT e.id , e.date , e.nombre , z.zone , s.espece
FROM echouage e
LEFT JOIN zone z ON z.id = e.zone_id
LEFT JOIN espece s ON s.id = e.espece_id
WHERE z.id = 1
AND s.id = 1
ORDER BY z.zone, s.espece, e.id ASC;
```

Recherche selon l'espece 
```sql
SELECT e.id , e.date , e.nombre , z.zone , s.espece
FROM echouage e
LEFT JOIN zone z ON z.id = e.zone_id
LEFT JOIN espece s ON s.id = e.espece_id
WHERE s.id = 1
ORDER BY z.zone, s.espece, e.id ASC;
```

Recherche selon la zone :
```sql
SELECT e.id , e.date , e.nombre , z.zone , s.espece
FROM echouage e
LEFT JOIN zone z ON z.id = e.zone_id
LEFT JOIN espece s ON s.id = e.espece_id
WHERE z.id = 1
ORDER BY z.zone, s.espece, e.id ASC;
```


Recherche de la quantité d'une espèce choisie dans chaque zones
```sql
SELECT  s.espece, z.zone, SUM(e.nombre) as `nombre`
FROM echouage e
LEFT JOIN zone z ON z.id = e.zone_id
LEFT JOIN espece s ON s.id = e.espece_id
WHERE s.id = 1
GROUP BY z.zone
ORDER BY z.zone, s.espece ASC;
```

Obtenir les dates d'une espèce
```sql
SELECT e.date FROM echouage e WHERE e.espece_id = 15 ORDER BY e.date ASC ;
```


Recherche de la quantité d'une espèce choisie dans chaque zones
```sql
SELECT  s.espece , e.date, z.zone, SUM(e.nombre) as `nombre`
FROM echouage e
LEFT JOIN zone z ON z.id = e.zone_id
LEFT JOIN espece s ON s.id = e.espece_id
WHERE s.id = 19
GROUP BY e.date , z.zone
ORDER BY e.date, z.zone ASC;
```

*affichage des stats d'une année*
```sql
    SELECT e.date, z.zone, SUM(e.nombre)
    FROM echouage e
    LEFT JOIN zone z ON z.id = e.zone_id
    LEFT JOIN espece s ON s.id = e.espece_id
    WHERE s.id = 1
    AND e.date = 1994
    GROUP BY e.date , z.zone
```

Il faudra également afficher un sous tableau affichant par zone, le 
nombre mini et maxi ainsi que la moyenne.

Afficher le nb minimal par zone
Afficher le nb maximal par zone
Afficher la moyenne par zone


+----+--------------------------------+--------------------------------------------+
| id | zone                           | mini        | maxi        | moyenne        |
+----+--------------------------------+-------------+-------------+----------------+
|  1 | Méditerranée                   |
|  2 | Manche Est - mer du Nord       |
|  3 | Nord Atlantique - Manche Ouest |
|  4 | Sud Atlantique                 |
+----+--------------------------------+

1. Afficher 1 espèce trié par zone d'abord, puis par date
```sql
SELECT  s.espece , e.date, z.zone, SUM(e.nombre) as `nombre`
FROM echouage e
LEFT JOIN zone z ON z.id = e.zone_id
LEFT JOIN espece s ON s.id = e.espece_id
WHERE s.id = 1
GROUP BY e.date , z.zone
ORDER BY z.zone, e.date ASC;
```
2. afficher 1 espèce dans 1 zone
```sql
SELECT  SUM(e.nombre) as `nombre`
FROM echouage e
LEFT JOIN zone z ON z.id = e.zone_id
LEFT JOIN espece s ON s.id = e.espece_id
WHERE s.id = 1
AND z.id=1
GROUP BY e.date , z.zone
ORDER BY nombre ASC;
```
