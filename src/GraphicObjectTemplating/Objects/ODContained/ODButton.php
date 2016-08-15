<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 26/02/16
 * Time: 17:10
 */

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;

/**
 * Class ODButton
 * @package GraphicObjectTemplating\Objects\ODContained
 *
 * type des boutons : (affecté suivant d'autre attribut)
 *      CUSTOM  = bouton divers permettant de déclencher un action
 *      SUBMIT  = bouton de déclenchement du pseudo formulaire auquel il est lié
 *      RESET   = bouton de réinitialisation du pseudo formulaire auquel il est lié
 *
 * setLabel     : affectation du texte présenté dans le bouton
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
 *      callback : "nomModule/nomObjet/nomMéthode"
 *          si nomObjet contient 'Controller' -> "nomModule/Controller/nomObjet/nomMéthode"
 *          si nomModule == 'Object' :
 *              si nomObjet commence par 'OD' -> "GraphicObjectTemplating/Objects/ODContained/nomObjet/nomMéthode"
 *              si nomObjet commence par 'OS' -> "GraphicObjectTemplating/Objects/ODContainer/nomObjet/nomMéthode"
 * disClick     : désactivation de lm'évènement 'click' sur le bouton
 * setNature    : affectation de la nature du bouton
 *      DEFAULT     : nature par défaut (valeur par défaut)
 *      PRIMARY     : nature primaire (bleu roi)
 *      SUCCESS     : nature succès (vert)
 *      INFO        : nature information (gris bleu)
 *      WARNING     : nature avertissement alerte (orange)
 *      DANGER      : nature danger, erreur (rouge)
 *      LINK        : nature lien (lien HTML, plus bouton alors)
 * getNature
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

    protected $const_btntype;
    protected $const_nature;

    public function __construct($id) {
        $parent = parent::__construct($id, "oobject/odcontained/odbutton/odbutton.config.phtml");
        $this->properties = $parent->properties;
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
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
        $callback   = $properties['event']['click'];
        switch(true) {
            case (empty($callback)):
                $properties['type'] = self::BTNTYPE_RESET;  break;
            case (!empty($callback)):
                $properties['type'] = self::BTNTYPE_SUBMIT; break;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function setType($type = self::BTNTYPE_CUSTOM)
    {
        $types = $this->getBtnTypesConstants();
        $type  = (string) $type;
        if (!in_array($type, $types)) $type = self::BTNTYPE_CUSTOM;

        if ($type == self::BTNTYPE_LINK) {
            if (isset($properties['event']['click']))
                $properties['event']['click'] = mb_strtolower($properties['event']['click']);
        }

        $properties         = $this->getProperties();
        $properties['type'] = $type;
        $this->setProperties($properties);
        return $this;
    }

    public function getType() {
        $properties         = $this->getProperties();
        return ((!empty($properties['type'])) ? $properties['type'] : false) ;
    }

    public function evtClick($callback)
    {
        $callback               = (string) $callback;
        $properties             = $this->getProperties();
        if(!isset($properties['event'])) $properties['event'] = [];
        if(!is_array($properties['event'])) $properties['event'] = [];

        $form     = $properties['form'];
        switch(true) {
            case (empty($form) && $properties['type'] != self::BTNTYPE_LINK ):
                $properties['type'] = self::BTNTYPE_CUSTOM; break;
            case (!empty($form)):
                $properties['type'] = self::BTNTYPE_SUBMIT; break;
        }
        
        if (isset($properties['type']) && ($properties['type'] == self::BTNTYPE_LINK)) {
            $callback = strtolower($callback);
        }

        $properties['event']['click'] = $callback;
        $this->setProperties($properties);
        return $this;
    }

    public function disClick()
    {
        $properties             = $this->getProperties();
        if (isset($properties['event']['click'])) unset($properties['event']['click']);
        $this->setProperties($properties);
        return $this;
    }

    public function setNature($nature = self::NATURE['DEFAULT'])
    {
        $natures = $this->getNatureConst();
        if (!in_array($nature, $natures)) $nature = self::NATURE_DEFAULT;

        $properties = $this->getProperties();
        $properties['nature'] = $nature;
        $this->setProperties($properties);
        return $this;
    }

    public function getNature() {
        $properties         = $this->getProperties();
        return ((!empty($properties['nature'])) ? $properties['nature'] : false) ;
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
                if ($pos !== false) $retour[$key] = $constant;
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
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_nature = $retour;
        } else {
            $retour = $this->const_nature;
        }
        return $retour;
    }

}