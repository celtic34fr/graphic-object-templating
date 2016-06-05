
Graphic Object Templating
=========================

Introduction
------------
Graphic Object Templating (GOT) est un framework pour construire des pages HTML5 avec des objets. Il permet aussi d'en gérer les inter-actions induites.
GOT s'appuie sur la sémentique HTML5 qu'il transforme en objet. Par ces derniers, il vise à faciliter la création et gestion de page inter et intranet.

Installation
------------
par composer, dans un shell à la racine de votre projet :
composer require celtic34fr/graphic-object-templating
    
Puis, tapez les commandes suivantes:
cd public
ln -s ../vendor/celtic34fr/grapic-object-templating/public ./graphic-object-templating
Ceci permet de mettre en oeuvre les liens vers les resources CSS et JS utiles à GOT.

Utilisation
-----------
pour utiliser GOT, il faut en premier lieux, utiliser ces objets pour créer page ou section, article de page.
Puis, il s'offre à vous 2 manières de générer les pages HTML5 de vos sites, applications :
* avec le service 'graphic-object-templating-services',
* avec des ViewHelpers dans vos templates de pages.