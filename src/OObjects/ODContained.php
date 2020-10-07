<?php


namespace GraphicObjectTemplating\OObjects;


use Exception;
use RuntimeException;

/**
 * Class ODContained
 * @package GraphicObjectTemplating\OObjects
 *
 * méthodes
 * --------
 * __construct(string $id, array $properties)
 * constructor($id, $properties) : array
 */

class ODContained extends OObject
{
    /**
     * ODContained constructor.
     * @param string $id
     * @param array $properties
     */
    public function __construct(string $id, array $properties)
    {
        parent::__construct($id, $properties);
        $this->properties = $this->constructor($id, $properties);
    }

    /**
     * @param string $id
     * @param array $properties
     * @return array
     */
    public function constructor($id, $properties) : array
    {
        $properties = parent::constructor($id, $properties);

        $path = __DIR__. '/../../params/oobjects/odcontained/odcontained.config.php';
        $odc_properties = require $path;
        $properties = $this->merge_properties($odc_properties, $properties);

        return $properties;
    }

}