Le service 'graphic-object-templating-services' donne une interface pour générer les différentes données HTML pour construire les pages.

méthodes :
* render : à partir d'un objet, quequ'en soit le type, restitue le code HTML pour afficher l'objet dans la page,
* header : génération des références des fichiers CSS / JS à charger dans la page pour l'affichage, la gestion de cette dernières dans la balise \<head> ... /</head>,
* bootstrapClass : génération de la chaîne de caractères des classes Bootstrap Twitter à affecter à un objet pour sa présentation.
