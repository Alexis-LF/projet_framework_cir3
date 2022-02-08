# requêtes MySQL

Recherche selon la zone et l'espece : MARCHE !!!
```sql
SELECT e.id , e.date , e.nombre , z.zone , s.espece
FROM echouage e
LEFT JOIN zone z ON z.id = e.zone_id
LEFT JOIN espece s ON s.id = e.espece_id
WHERE z.id = 1
AND s.id = 1
ORDER BY z.zone, s.espece, e.id ASC;
```

Recherche selon l'espece : MARCHE !!!
```sql
SELECT e.id , e.date , e.nombre , z.zone , s.espece
FROM echouage e
LEFT JOIN zone z ON z.id = e.zone_id
LEFT JOIN espece s ON s.id = e.espece_id
WHERE s.id = 1
ORDER BY z.zone, s.espece, e.id ASC;
```

Recherche selon l'espece explicite :
```sql
SELECT echouage.id , echouage.date , echouage.nombre , zone.zone , espece.espece
FROM echouage
LEFT JOIN zone ON zone.id = echouage.zone_id
LEFT JOIN espece ON espece.id = echouage.espece_id
WHERE espece.id = 1
ORDER BY zone.zone, espece.espece, echouage.id ASC
```

Recherche selon la zone : MARCHE !!!
```sql
SELECT e.id , e.date , e.nombre , z.zone , s.espece
FROM echouage e
LEFT JOIN zone z ON z.id = e.zone_id
LEFT JOIN espece s ON s.id = e.espece_id
WHERE z.id = 1
ORDER BY z.zone, s.espece, e.id ASC;
```


Recherche de la quantité d'une espèce dans chaque zones
```sql
SELECT  s.espece, z.zone, count(*) as `nombre`
FROM echouage e
LEFT JOIN zone z ON z.id = e.zone_id
LEFT JOIN espece s ON s.id = e.espece_id
WHERE s.id = 1
GROUP BY z.zone
ORDER BY z.zone, s.espece ASC;
`