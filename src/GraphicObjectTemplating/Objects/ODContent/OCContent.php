<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 14:43
 */

namespace GraphicObjectTemplating\Objects\ODContent;

use GraphicObjectTemplating\Objects\ODContent;
use Zend\Config\Config;

/**
 * Class OCContent
 * @package GraphicObjectTemplating\Objects\ODContent
 *
 * setContent
 * getContent
 * setValue
 * getValue
 */
class OCContent extends  ODContent
{
    public function __construct($id) {
        parent::__construct($id, "oobject/odcontent/occontent/occontent.config.phtml");
        $this->setDisplay();
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