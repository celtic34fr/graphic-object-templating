l'objet ODButton
----------------

On peut affecter à un bouton un type de fonctionnement : (pouvant être réaffecté suivant d'autre attribut)
    CUSTOM  = bouton divers permettant de déclencher un action
    SUBMIT  = bouton de déclenchement du pseudo formulaire auquel il est lié
    RESET   = bouton de réinitialisation du pseudo formulaire auquel il est lié
    LINK    = lien pour accès à une resource interne ou externe au site à l'application (type HTML)

L'affectation de la nature à un bouton en détermine son aspect (couleur de fond) :
    DEFAULT = nature par défaut (valeur par défaut)
    PRIMARY = nature primaire (bleu roi)
    SUCCESS = nature succès (vert)
    INFO    = nature information (gris bleu)
    WARNING = nature avertissement alerte (orange)
    DANGER  = nature danger, erreur (rouge)
    LINK    = nature lien (lien HTML, plus bouton alors)
Cette nature peut de fait devenir signifiante suivant le contexte de l'emploi du bouton.

Le seul évènement géré sur le bouton est le click. À ce moment, le programme fourni la référence de la mérthode (callback) à exécuter

On peut, comme sur tout objet, avoir une bulle d'aide information grâce aux fonctions liées à l'attribut infoBulle.
Cette information pourra prendre 2 aspects : information sur une ligne (tooltip), panneau explicatif (popover).

le contenu du bouton peut être texte (affectation d'un label) et/ou icône (affectation d'une font awesome / glyphicon par classe suivbant disponibilité)

Méthode de l'objet ODButton
---------------------------

Il contient les méthodes communes aux objets [OObject](OObject.md)  et [ODContained](ODContained.md) avant d'avoir des méthodes spéfifiques :
