
Graphic Object Templating
=========================

Introduction
------------
Graphic Object Templating (GOT) est un framework pour construire des pages HTML5 avec des objets. Il permet aussi d'en gérer les inter-actions induites.
GOT s'appuie sur la sémentique HTML5 qu'il transforme en objet. Par ces derniers, il vise à faciliter la création et gestion de page inter et intranet.

Les objets que met en oeuvre GOT, sont de 2 types :
* les [objets de type contenu](doc/objectDataContent.md) : présente, aide à l'aciquisition de données,
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

Il faut ajouter dans votre modèle 'layout.html.twig' la ligne suivante pour permettre les appels Ajax utile à G.O.T.
Il faut la mettre juste au début du block (balise div) ayant pour classe 'container'

``<div id="gotCallback" style="display: none;">{{ url("got/callback") }}</div>``


Utilisation
-----------
pour utiliser GOT, il faut en premier lieux, utiliser ces objets pour créer page ou section, article de page.
Puis, il s'offre à vous 2 manières de générer les pages HTML5 de vos sites, applications :
* avec le [service 'graphic-object-templating-services'](doc/service.md) ,
* avec des [ViewHelpers](doc/viewHelpers.md) dans vos templates de pages.

Objets actuellement disponibles
-------------------------------
* [OCButton](doc/objets/OCButton.md)    : bouton (avec exécution de méthode [callback] possible) 
* OCContent   : objet pour affichage de contenu non typé 
* OCInput     : zone de saisie standart [pris en charge : texte (text), caché (hidden) et mot de passe (password)] 
* OSDiv       : section non typé de page html 

En cours de développement : 
* OCSelect    : liste déroulante (HTML ou jQuery Select2) 
* OCCheckbox  : case à cocher 
* OCRadio     : radio bouton 
* OCMessage   : affichage de message à l'écran (façon Windows 8)