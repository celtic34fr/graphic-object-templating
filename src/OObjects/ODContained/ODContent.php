<?php

namespace GraphicObjectTemplating\OObjects\ODContained;

use GraphicObjectTemplating\OObjects\ODContained;

/**
 * Class ODContent
 * @package GraphicObjectTemplating\Objects\ODContained
 *
 * setContent   : affecte un contenu à l'objet
 * getContent   : réstitue le contenu actuelle de l'objet
 * setValue     : alias pour setContenu
 * getValue     : alias pour getContenu
 * evtClick     : affectation et activation d'un évènement click sur l'objet
 * disClisk     : déactivation de l'évènement click
 */
class ODContent extends  ODContained
{
    public function __construct($id) {
        parent::__construct($id, "oobjects/odcontained/odcontent/odcontent.config.php");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
        $this->enable();
        return $this;
    }

    public function setContent($content)
    {
        $content               = (string) $content;
        $properties            = $this->getProperties();
        $properties['content'] = $content;
        $properties['value']   = $content;
        $this->setProperties($properties);
        return $this;
    }

    public function getContent()
    {
        $properties            = $this->getProperties();
        return ((array_key_exists('content', $properties)) ? $properties['content'] : false);
    }

    public function setValue($content)
    {
        $content               = (string) $content;
        $properties            = $this->getProperties();
        $properties['content'] = $content;
        $properties['value']   = $content;
        $this->value           = $content;
        $this->setProperties($properties);
        return $this;
    }

    public function getValue()
    {
        $properties            = $this->getProperties();
        return ((array_key_exists('content', $properties)) ? $properties['content'] : false);
    }

    public function evtClick($class, $method, $stopEvent = true)
    {
        $properties = $this->getProperties();
        if(!isset($properties['event'])) $properties['event'] = [];
        $properties['event']['click'] = [];
        $properties['event']['click']['class'] = $class;
        $properties['event']['click']['method'] = $method;
        $properties['event']['click']['stopEvent'] = ($stopEvent) ? 'OUI' : 'NON';
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

}