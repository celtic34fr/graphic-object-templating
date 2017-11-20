<?php

namespace GraphicObjectTemplating\OObjects\ODContained;

use GraphicObjectTemplating\OObjects\ODContained;
use Zend\Session\Container;

/**
 * Class ODButton
 * @package GraphicObjectTemplating\Objects\ODContained
 *
 * T : testé et validé
 *
 * type des boutons : (affecté suivant d'autre attribut)
 * T      CUSTOM  = bouton divers permettant de déclencher un action
 *      SUBMIT  = bouton de déclenchement du pseudo formulaire auquel il est lié
 *      RESET   = bouton de réinitialisation du pseudo formulaire auquel il est lié
 *
 * T setLabel     : affectation du texte présenté dans le bouton
 * getLabel     : récupération du texte présenté dans le bouton
 * setIcon      : affecte une icône au bouton (font awesome / glyphicon)
 * getIcon      : récupère le nom de l'icône affecté au bouton
 * setForm      : surchange de la méthode d'affectation de l'identifiant de regroupement (simulation de formulaire)
 *                  peut induire une modification du type du bouton
 * setType      : affectation du type de bouton
 *      CUSTOM      : type divers
 *      SUBMIT      : type soumission (de formuulaire)
 *      RESET       : type remise à zéro des champs (de formulaire)
 *      LINK        : type lien HTML
 * getType      : récupération du type du bouton
 * evtClick     : activation et paramètrage de l'évènement 'click' sur le bouton
 *      object : "nomObjet" (nom de la classe avec son namespace pour déclaration d'objet)
 *      method ; "nomMéthode" (nom proprement de la méthode à exécuter)
 * REMARQUE pour créer des objets composés GOT il est conseillé :
 *      -> de créer un répertoire GotObjects pour contenir les objets GOT du module
 *      -> de terminer obligatoirement le nom de la classe définissant l'objet par 'GOT'
 *          ceci permettra de coder les méthodes de traitement de l'objet dans la classe elle-même
 *          la base prise sera une extension de l'objet OSDiv comme contenant global.
 * disClick     : désactivation de lm'évènement 'click' sur le bouton
 * T setNature    : affectation de la nature du bouton
 *      DEFAULT     : nature par défaut (valeur par défaut)
 *      PRIMARY     : nature primaire (bleu roi)
 * §     SUCCESS     : nature succès (vert)
 *      INFO        : nature information (gris bleu)
 * §     WARNING     : nature avertissement alerte (orange)
 *      DANGER      : nature danger, erreur (rouge)
 *      LINK        : nature lien (lien HTML, plus bouton alors)
 * T getNature    : restitue la valeur de l'attribut nature (Cf. les constatntes associées)
 * getEvent($evt)   : restitue les parametres de l'évènement $evt s'il existe pour le bouton
 *                    traitement particulier des boutons de type lien => tableau des paramètres en chaînes de caractères
 */
class ODButton extends ODContained
{
    const BTNTYPE_CUSTOM = 'custom';
    const BTNTYPE_SUBMIT = 'submit';
    const BTNTYPE_RESET  = 'reset';
    const BTNTYPE_LINK   = 'link';

    const NATURE_DEFAULT = "btn btn-default";
    const NATURE_PRIMARY = 'btn btn-primary';
    const NATURE_SUCCESS = 'btn btn-success';
    const NATURE_INFO    = 'btn btn-info';
    const NATURE_WARNING = 'btn btn-warning';
    const NATURE_DANGER  = 'btn btn-danger';
    const NATURE_LINK    = 'btn btn-link';

    const LINK_TARGET_BLANK  = '_black';
    const LINK_TARGET_SELF   = '_self';
    const LINK_TARGET_PARENT = '_parent';
    const LINK_TARGET_TOP    = '_top';

    protected $const_btntype;
    protected $const_nature;

    public function __construct($id)
    {
        parent::__construct($id, 'oobjects/odcontained/odbutton/odbutton.config.php');
        $this->id = $id;
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) {
            $this->setWidthBT(12);
        }
        $this->enable();
        return $this;
    }

    public function setLabel($label)
    {
        $label               = (string) $label;
        $properties          = $this->getProperties();
        $properties['label'] = $label;
        $this->setProperties($properties);
        return $this;
    }

    public function getLabel()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['label'])) ? $properties['label'] : false) ;
    }

    public function setIcon($icon)
    {
        $icon               = (string) $icon;
        $properties         = $this->getProperties();
        $properties['icon'] = $icon;
        $this->setProperties($properties);
        return $this;
    }

    public function getIcon()
    {
        $properties         = $this->getProperties();
        return ((!empty($properties['icon'])) ? $properties['icon'] : false) ;
    }

    public function setForm($form)
    {
        parent::setForm($form);

        $properties = $this->getProperties();
        $callback = isset($properties['event']['click']) ? $properties['event']['click'] : '';
        if (empty($properties['type']) || $properties['type'] !== self::BTNTYPE_LINK) {
            switch (true) {
                case (empty($callback)):
                    $properties['type'] = self::BTNTYPE_RESET;
                    break;
                case (!empty($callback)):
                    $properties['type'] = self::BTNTYPE_SUBMIT;
                    break;
            }
        } else {
            if (!empty($properties['type']) && isset($properties['form'])) {
                $properties['form'] = '';
            }
        }
        $this->setProperties($properties);
        return $this;
    }

    public function setType($type = self::BTNTYPE_CUSTOM)
    {
        $types = $this->getBtnTypesConstants();
        $type = (string)$type;
        if (!in_array($type, $types, true)) {
            $type = self::BTNTYPE_CUSTOM;
        }
        $properties = $this->getProperties();

        switch ($type) {
            case self::BTNTYPE_LINK:
                if (isset($properties['form'])) {
                    $properties['form'] = '';
                }
                if (isset($properties['event']['click']) && !empty($properties['event']['click'])) {
                    $method = $properties['event']['click']['method'];
                    if (!is_array($method)) {
                        $method = explode('|', $method);
                        $params = [];
                        foreach ($method as $item) {
                            $item = explode(':', $item);
                            $params[$item[0]] = $item[1];
                        }
                        $properties['event']['click']['method'] = $params;
                    }
                }
                break;
            case self::BTNTYPE_RESET:
                if (empty($properties['form'])) {
                    $type = self::BTNTYPE_CUSTOM;
                }
                break;
            case self::BTNTYPE_SUBMIT:
                if (empty($properties['form']) || !isset($properties['event']['click'])) {
                    $type = self::BTNTYPE_CUSTOM;
                }
                break;
        }

        $properties['type'] = $type;
        $this->setProperties($properties);
        return $this;
    }

    public function getType() {
        $properties         = $this->getProperties();
        return ((!empty($properties['type'])) ? $properties['type'] : false) ;
    }

    public function evtClick($class, $method, $stopEvent = true)
    {
        $class = (string)$class;
        $method = (string)$method;
        $properties = $this->getProperties();
        if (!isset($properties['event'])) {
            $properties['event'] = [];
        }
        if (!is_array($properties['event'])) {
            $properties['event'] = [];
        }

        $properties['event']['click'] = [];
        $properties['event']['click']['class'] = $class;
        $properties['event']['click']['method'] = $method;
        $properties['event']['click']['stopEvent'] = ($stopEvent) ? 'OUI' : 'NON';

        $form = $properties['form'];
        switch (true) {
            case (empty($form) && $properties['type'] !== self::BTNTYPE_LINK):
                $properties['type'] = self::BTNTYPE_CUSTOM;
                break;
            case (!empty($form)):
                $properties['type'] = self::BTNTYPE_SUBMIT;
                break;
        }

        if (isset($properties['type']) && ($properties['type'] === self::BTNTYPE_LINK)) {
            $properties['event']['click']['class'] = $class;
            $method = explode('|', $method);
            $params = [];
            foreach ($method as $item) {
                $item = explode(':', $item);
                if (count($item) > 1) {
                    $params[$item[0]] = $item[1];
                }
            }
            $properties['event']['click']['params'] = $params;
        }

        $this->setProperties($properties);
        return $this;
    }

    public function getClick()
    {
        $properties = $this->getProperties();
        if (array_key_exists('event', $properties)) {
            $event = $properties['event'];
            if (array_key_exists('clic', $event)) { return $event['clic']; }
        }
    }

    public function disClick()
    {
        $properties = $this->getProperties();
        if (isset($properties['event']['click'])) {
            unset($properties['event']['click']);
        }
        $this->setProperties($properties);
        return $this;
    }

    public function setNature($nature = self::NATURE_DEFAULT)
    {
        $natures = $this->getNatureConst();
        if (!in_array($nature, $natures, true)) {
            $nature = self::NATURE_DEFAULT;
        }

        $properties = $this->getProperties();
        $properties['nature'] = $nature;
        $this->setProperties($properties);
        return $this;
    }

    public function getNature()
    {
        $properties = $this->getProperties();
        return ((!empty($properties['nature'])) ? $properties['nature'] : false);
    }

    /*
     * méthode interne à la classe OObject
     */

    private function getBtnTypesConstants()
    {
        $retour = [];
        if (empty($this->const_btntype)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'BTNTYPE');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_btntype = $retour;
        } else {
            $retour = $this->const_btntype;
        }
        return $retour;
    }

    private function getNatureConst()
    {
        $retour = [];
        if (empty($this->const_nature)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'NATURE');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_nature = $retour;
        } else {
            $retour = $this->const_nature;
        }
        return $retour;
    }

    public function getEvent($evt)
    {
        $properties = $this->getProperties();
        $ret = [];
        if (array_key_exists('event', $properties)) {
            $events = $properties['event'];
            if (array_key_exists($evt, $events)) {
                $ret['class'] = $events[$evt]['class'];
                $method = $events[$evt]['params'];
                if ($properties['type'] == self::BTNTYPE_LINK) {
                    foreach ($method as $key => $item) {
                        $method[$key] = $key.":".$item;
                    }
                    $method = implode('|', $method);
                }
                $ret['method'] = $method;
                $ret['stopEvent'] = ($events[$evt]['stopEvent'] === 'OUI');
            }
        }
        return $ret;
    }
}