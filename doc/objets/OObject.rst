Méthode de l'objet parent OObject
---------------------------------

+-------------------+---------------------------------------------------------------------------------------------------+
| Méthode           + Fonctionnalité                                                                                    +
+===================+===================================================================================================+
|initProperties     | initialise le tableau des propriétés de l’objet à partir d’un fichier de configuration            |
+-------------------+---------------------------------------------------------------------------------------------------+
|mergeProperties    | ajoute au tableau des propriétés de l’objet de nouveaux attributs (avec un tableau)               |
+-------------------+---------------------------------------------------------------------------------------------------+
|setProperties      | affecte et sauvegarde en session de tableau des propriétés de l’objet avec un tableau externe     |
+-------------------+---------------------------------------------------------------------------------------------------+
|getProiperties	    | restitue le tableau en session des propriété de l’objet                                           |
+-------------------+---------------------------------------------------------------------------------------------------+
|setId              | affecte l’attribut identifiant de l’objet (balise HTML id)                                        |
+-------------------+---------------------------------------------------------------------------------------------------+
|getId              | restitue l’attribut identifiant de l’objet                                                        |
+-------------------+---------------------------------------------------------------------------------------------------+
|setName            | affecte l’attribut nom de l’objet (balise HTML name)                                              |
+-------------------+---------------------------------------------------------------------------------------------------+
|getName            | restitue l’attribut nom de l’objet                                                                |
+-------------------+---------------------------------------------------------------------------------------------------+
|setTemplate        | affecte le chemin d’accès au modèle à rendre de l’objet à l’attribut ‘template’                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|getTemplate        | restitue le chemin d’accès au modèle à rendre de l’objet                                          |
+-------------------+---------------------------------------------------------------------------------------------------+
|setDisplay         | affecte le mode d’affichage de l’objet (bloc, intégré (inline) ou mixte (inline-block))           |
+-------------------+---------------------------------------------------------------------------------------------------+
|getDisplay         | restitue le mode d’affichage de l’objet                                                           |
+-------------------+---------------------------------------------------------------------------------------------------+
|setStyle           | affecte un style (CSS) à l’objet au travers de l’attribut css                                     |
+-------------------+---------------------------------------------------------------------------------------------------+
|addStyle           | ajoute au style présent de l’objet de nouvelles directives                                        |
+-------------------+---------------------------------------------------------------------------------------------------+
|getStyle           | restitue la chaîne de caractères des directives de style (CSS) de l’objet                         |
+-------------------+---------------------------------------------------------------------------------------------------+
|setClasses         | permet l’affectation d’une ou plusieurs classes d’affichage (CSS) à l’objet                       |
+-------------------+---------------------------------------------------------------------------------------------------+
|addClasses         | ajoute au classes présentes de l’objet de nouvelles                                               |
+-------------------+---------------------------------------------------------------------------------------------------+
|getClasses         | restitue la chaîne de caractères des classes de présentation (CSS) de l’objet                     |
+-------------------+---------------------------------------------------------------------------------------------------+
|setInfoBulle       | permet d’affecter une infoBulle (texte d’explication) à l’objet                                   |
|                   |  - title :   titre de l’infoBulle (utile dans le cas d’affichage type POPOVER), il est obligatoire|
|                   |  - content :   le texte des explications proprement dit, facultatif                               |
|                   |  - type :   type d’affichage : TOOLTIP ou POPOVER (par défaut : TOOLTIP si absent)                |
+-------------------+---------------------------------------------------------------------------------------------------+
|getInfoBulle       | restitution du tableau de paramétrage de l’infoBulle                                              |
+-------------------+---------------------------------------------------------------------------------------------------+
|setInfoBulleParams | affectation d’attributs par un tableau, à l’infoBulle                                             |
|                   | title : titre de l’infoBulle (utile dans le cas d’affichage type POPOVER), il est obligatoire     |
|                   | content : le texte des explications proprement dit, facultatif                                    |
|                   | html : booléen indiquant si titre et explication sont des contenus HTML ou non                    |
|                   | type : type d’affichage : TOOLTIP ou POPOVER                                                      |
|                   | placement : TOP / LEFT / BOTTOM / RIGHT : placement standard                                      |
|                   | trigger : sur clic (CLICK), survol (HOVER), acquisition du focus (FOCUS)                          |
|                   | delay-show  :   temps pour afficher l’infoBulle en millisecondes (défaut : 0)                     |
|                   | delay-hide  :   temps de disparition de l’infoBulle en millisecondes (defaut:400)                 |
+-------------------+---------------------------------------------------------------------------------------------------+
|setWidthBT         | permet d’affecter la largeur de l’objet dans une grille Bootstreap Twitter                        |
|	            | valeur numérique	    : même taille quelque soit le média                                         |
|	            | O+chiffrte:W+chiffre    : O signale un offset                                                     |
|	            | (décalage en nombre de colonne), W une largeur pour tous les médias                               |
|	            | W+[X,S,M,L]+chiffre	    : ici largeur pour chaque média X : xs, S : sm, M : md, L : lg      |
|	            | On peut faire de même avec O                                                                      |  
|	            | REMARQUE : on sépare largeur et offset par 2 point (semi-colon)                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|getWidthBT	    | restitue un tableau de 8 valeur donnant largeur et offset pour chaque média                       |
+-------------------+---------------------------------------------------------------------------------------------------+
|static buildObject | méthode statique permettant de reconstruire un objet à partir                                     |
|                   | de son identifiant et de ses propriétés sauvegardées en session                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|getResources       | restitue le tableau des ressources ou fichiers externe nécessaires au fonctionnement de l’objet   |
+-------------------+---------------------------------------------------------------------------------------------------+
|addCssResource	    | ajoute au tableau des ressources,                                                                 |
|                   | la référence d’un fichier CSS (présent dans le répertoire public de GOT)                          |
+-------------------+---------------------------------------------------------------------------------------------------+
|getCssResource	    | restitue le tableau des ressources CSS (fichiers de présentation) de l’objet                      |
+-------------------+---------------------------------------------------------------------------------------------------+
|setCssResource	    | affecte une ressources ou un groupe de ressource (via tableau) CSS à l’objet                      |
+-------------------+---------------------------------------------------------------------------------------------------+
|addJsResource	    | ajoute au tableau des ressources, la référence d’un fichier JS                                    |
|                   | (présent dans le répertoire public de GOT)                                                        |
+-------------------+---------------------------------------------------------------------------------------------------+
|getJsResource      | restitue le tableau des ressources JS (fichiers de présentation) de l’objet                       |
+-------------------+---------------------------------------------------------------------------------------------------+
|setJsResource      | affecte une ressources ou un groupe de ressource (via tableau) JS à l’objet                       |
+-------------------+---------------------------------------------------------------------------------------------------+
|setAclReference    |                                                                                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|getAclReference    |                                                                                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|setObject          |                                                                                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|getObject          |                                                                                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|setTypeObj         |                                                                                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|getTypeObj         |                                                                                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|setClassName       |                                                                                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|getClassName       |                                                                                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|enable             |                                                                                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|disable            |                                                                                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|getState           |                                                                                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|setErreur          |                                                                                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
|getErreur          |                                                                                                   |
+-------------------+---------------------------------------------------------------------------------------------------+
