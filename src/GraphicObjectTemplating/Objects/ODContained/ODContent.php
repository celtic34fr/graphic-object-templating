<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 14:43
 */

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;

/**
 * Class ODContent
 * @package GraphicObjectTemplating\Objects\ODContained
 *
 * setContent   : affecte un contenu à l'objet
 * getContent   : réstitue le contenu actuelle de l'objet
 * setValue     : alias pour setContenu
 * getValue     : alias pour getContenu
 */
class ODContent extends  ODContained
{
    public function __construct($id) {
        parent::__construct($id, "oobject/odcontained/odcontent/odcontent.config.phtml");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
    }

    public function setContent($content)
    {
        $content               = (string) $content;
        $properties            = $this->getProperties();
        $properties['content'] = $content;
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
        $this->setProperties($properties);
        return $this;
    }

    public function getValue()
    {
        $properties            = $this->getProperties();
        return ((array_key_exists('content', $properties)) ? $properties['content'] : false);
    }

}