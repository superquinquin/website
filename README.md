Superquinquin Website 

#plugins 

plugins : 
cd plugins 
svn co svn://zone.spip.org/spip-zone/_squelettes_/html5up_alpha html5up_alpha
svn co svn://zone.spip.org/spip-zone/_plugins_/csv2auteurs/trunk csv2auteurs 


# Plugin coop_membre 

## Input 
Dans une première version ce plugin importe un fichier CSV avec les infos provenant d'odo.

Un exemple de fichier : 
```
barcode,sex,surname,name,email,city,is_deceased,is_member,is_worker_member,is_associated_people
0420001000005,m, Georges Dupond,georges@dupond.fr,Hellemmes Lille,False,True,True,False
0420001001002,f, Jacqueline Dupont ,jacqueline@dupont.fr,Lille,False,True,True,False
```

## Exécution 

ecrire/?exec=importer_coop_membre

Il y a une première étape de vérification des données afin de vérifier que le format est correct. Puis une étape d'import des membres.

En sortie : 
```
Nombre de lignes du fichier : 1316
Déjà dans la base : 13
Pas d'email renseignés : 256
Coopérateurs importés : 1047
```
Si on relance le même import : 

```
Nombre de lignes du fichier : 1316
Déjà dans la base : 1060
Pas d'email renseignés : 256
Coopérateurs importés : 0
```


# Todo 
- Aller directement chercher l'info dans odoo 
- page importer_coop_membre uniquement pour les administrateurs 
- erreur installation plugin 
- gérer les changement de nom/prénom : faute de frappe sur prénom 


