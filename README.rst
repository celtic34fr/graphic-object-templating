
Graphic Object Templating
=========================

Introduction
------------
Graphic Object Templating (GOT) est un framework pour construire des pages HTML5 avec des objets. Il permet aussi d'en gérer les inter-actions induites.
GOT s'appuie sur la sémentique HTML5 qu'il transforme en objet. Par ces derniers, il vise à faciliter la création et gestion de page inter et intranet.

Les objets que met en oeuvre GOT, sont de 2 types :
* les [objets de type contenu](doc/objectDataContained.md) : présente, aide à l'aciquisition de données,
* les [objets de type contenant](doc/objectStructureContainer.md) : aide à l'organisation de la page et à la présention des informations à travers d'autres objets quelqu'en soit le type

Installation
------------
par composer, dans un shell à la racine de votre projet :

``composer require celtic34fr/graphic-object-templating``
    
Puis, tapez les commandes suivantes:

``cd public``

Unix    :``ln -s ../vendor/celtic34fr/graphic-object-templating/GraphicObjectTemplating/public ./graphicobjecttemplating``

Windows :``mklink .\graphicobjecttemplating ..\vendor\celtic34fr\graphic-object-templating\GraphicObjectTemplating\public``

Ceci permet de mettre en oeuvre les liens vers les resources CSS et JS utiles à GOT.

Il faut ajouter dans votre modèle 'layout.html.twig' les lignes suivantes pour permettre les appels Ajax utile à G.O.T. dans le bloc head :

``<link href="{{ basePath() }}/graphicobjecttemplating/gotMain/css/main.css" media="screen" rel="stylesheet" type="text/css">
<link href="{{ basePath() }}/graphicobjecttemplating/gotMain/css/awesome-bootstrap-checkbox.css" media="screen" rel="stylesheet" type="text/css">
<link href="{{ basePath() }}/graphicobjecttemplating/gotMain/css/font-awesome.css" media="screen" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{{ basePath() }}/graphicobjecttemplating/gotMain/js/main.js"></script>``

Et il faut ajouter les deux lignes suivantes au début du bloc body

``<div id="gotCallback" style="display: none;">{{ url("got/callback") }}</div>
<div id="divWork" style="display: none"></div>``


Utilisation
-----------
pour utiliser GOT, il faut en premier lieux, utiliser ces objets pour créer page ou section, article de page.
Puis, il s'offre à vous 2 manières de générer les pages HTML5 de vos sites, applications :
* avec le [service 'graphic-object-templating-services'](doc/service.md),
* avec des [ViewHelpers](doc/viewHelpers.md) dans vos templates de pages.

Objets actuellement disponibles
-------------------------------
* [ODButton](doc/objets/ODButton.md)    : bouton (avec exécution de méthode [callback] possible) 
* ODContent   : objet pour affichage de contenu non typé 
* ODInput     : zone de saisie standart [pris en charge : texte (text), caché (hidden) et mot de passe (password)] 
* OSDiv       : section non typé de page html 

En cours de développement : 
* ODSelect    : liste déroulante (HTML ou jQuery Select2) 
* ODCheckbox  : case à cocher 
* ODRadio     : radio bouton 
* ODMessage   : affichage de message à l'écran (façon Windows 8)
