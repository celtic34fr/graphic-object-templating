
Graphic Object Templating
=========================

Introduction
------------
Graphic Object Templating (GOT) est un framework pour construire des pages HTML5 avec des objets. Il permet aussi d'en gérer les inter-actions induites.
GOT s'appuie sur la sémentique HTML5 qu'il transforme en objet. Par ces derniers, il vise à faciliter la création et gestion de page inter et intranet.

Les objets que met en oeuvre GOT, sont de 2 types :

- les objets de type contenu_  : présente, aide à l'aciquisition de données,
- les objets de type contenant_  : aide à l'organisation de la page et à la présention des informations à travers d'autres objets quelqu'en soit le type

Installation
------------
par composer, dans un shell à la racine de votre projet

::

    composer require celtic34fr/graphic-object-templating
    
Puis, tapez les commandes suivantes::

    cd public

Unix::

    ln -s ../vendor/celtic34fr/graphic-object-templating/GraphicObjectTemplating/public ./graphicobjecttemplating

Windows::

    mklink .\graphicobjecttemplating ..\vendor\celtic34fr\graphic-object-templating\GraphicObjectTemplating\public

Ceci permet de mettre en oeuvre les liens vers les resources CSS et JS utiles à GOT.

Il faut ajouter dans votre modèle 'layout.html.twig' les lignes suivantes pour permettre les appels Ajax utile à G.O.T. dans le bloc head :

::

    <link href="{{ basePath() }}/graphicobjecttemplating/gotMain/css/main.css" media="screen" rel="stylesheet" type="text/css">
    <link href="{{ basePath() }}/graphicobjecttemplating/gotMain/css/awesome-bootstrap-checkbox.css" media="screen" rel="stylesheet" type="text/css">
    <link href="{{ basePath() }}/graphicobjecttemplating/gotMain/css/font-awesome.css" media="screen" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ basePath() }}/graphicobjecttemplating/gotMain/js/main.js"></script>

Et il faut ajouter les deux lignes suivantes au début du bloc body

::

    <div id="gotCallback" style="display: none;">{{ url("got/callback") }}</div>
    <div id="divWork" style="display: none"></div>

puis on doit activer les modules dans le fichier config\application.config.php :
::

        'ZfcTwig',
        'GraphicObjectTemplating',

Dans le répertoire /view/templates de remplacement se trouve les templates de substitutions à ceux existant dans le répertoire view du module Application.
Après les avoir copiés dans les répertoires respectif, ilm vous faudra modifier le fichier de configuration dui module Application :

::
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.html.twig',
            'application/index/index' => __DIR__ . '/../view/application/index/index.html.twig',
            'error/404'               => __DIR__ . '/../view/error/404.html.twig',
            'error/index'             => __DIR__ . '/../view/error/index.html.twig',
        ),

Utilisation
-----------
pour utiliser GOT, il faut en premier lieux, utiliser ces objets pour créer page ou section, article de page.
Puis, il s'offre à vous 2 manières de générer les pages HTML5 de vos sites, applications :

* avec le service_ 'graphic-object-templating-services',
* avec des ViewHelpers_ dans vos templates de pages.

Objets actuellement disponibles
-------------------------------
* ODButton_ : bouton (avec exécution de méthode [callback] possible)
* ODContent_ : objet pour affichage de contenu non typé
* ODInput_ : zone de saisie standart [pris en charge : texte (text), caché (hidden) et mot de passe (password)]
* OSDiv : section non typé de page html

En cours de développement :

* ODSelect_ : liste déroulante (HTML ou jQuery Select2)
* ODCheckbox_ : case à cocher
* ODRadio_ : radio bouton
* ODMessage : affichage de message à l'écran (façon Windows 8)
* ODTable_ : objet balise HTML <table></table>

.. _ODButton: doc/objets/ODButton.rst
.. _ODContent: doc/objets/ODContent.rst
.. _ODCheckbox: doc/objets/ODCheckbox.rst
.. _ODInput: doc/objets/ODInput.rst
.. _ODRadio: doc/objets/ODRadio.rst
.. _ODSelect: doc/objets/ODSelect.rst
.. _ODTable: doc/objets/ODTable.rst

.. _contenu: doc/objectDataContained.rst
.. _contenant: doc/objectStructureContainer.rst
.. _service: doc/service.rst
.. _ViewHelpers: doc/viewHelpers.rst