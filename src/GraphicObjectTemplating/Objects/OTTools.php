<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 10:42
 */

namespace GraphicObjectTemplating\Objects;

use GraphicObjectTemplating\Objects\OObject;

/**
 * Class OSContainer
 * @package GraphicObjectTemplating\Objects
 *
 */
class OTTools extends OObject
{

    public function __construct($id, $adrProperties)
    {
        $properties = include(__DIR__ ."/../../../view/graphic-object-templating/oobject/ottools/ottools.config.php");
        parent::__construct($id, $adrProperties);
        $properties = array_merge($this->getProperties(), $properties);
        $this->setProperties($properties);
    }

}