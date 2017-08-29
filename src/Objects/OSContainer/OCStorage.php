<?php

namespace GraphicObjectTemplating\Objects\OSContainer;

use GraphicObjectTemplating\Objects\OSContainer;

/**
 * Class OCStorage
 * @package GraphicObjectTemplating\Objects\OSContainer
 *
 * setForm($form)
 * getForm()
 * setMethod($method = self::STORAGE_METHOD_ÇLOCALSTORAGE)
 * getMethod()
 */
class OCStorage extends OSContainer
{
    const STORAGE_METHOD_LOCALSTORAGE   = "localStorage";
    const STORAGE_METHOD_SESSIONSTORAGE = "sessionStorage";
    const STORAGE_METHOD_SESSION        = "session";

    protected $const_storageMethod;

    public function __construct($id)
    {
        parent::__construct($id, "oobject/oscontainer/osstorage/osstorage.config.php");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
    }

    public function setForm($form)
    {
        $form = (string) $form;
        $properties = $this->getProperties();
        $properties['form'] = $form;
        $this->setProperties($properties);
        return $this;
    }

    public function getForm()
    {
        $properties = $this->getProperties();
        return ((!empty($properties['form'])) ? $properties['form'] : false) ;
    }

    public function setMethode($method = self::STORAGE_METHOD_LOCALSTORAGE)
    {
        $method = (string) $$method;
        $methods = $this->getStorageMethodeConstants();
        if (!in_array($method, $methods)) $method = self::STORAGE_METHOD_LOCALSTORAGE;

        $properties = $this->getProperties();
        $properties['method'] = $method;
        $this->setProperties($properties);
        return $this;
    }

    public function getMethod()
    {
        $properties = $this->getProperties();
        return ((!empty($properties['method'])) ? $properties['method'] : false) ;
    }


    private function getStorageMethodeConstants()
    {
        $retour = [];
        if (empty($this->const_storageMethod)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'STORAGE_METHOD');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_storageMethod = $retour;
        } else {
            $retour = $this->const_storageMethod;
        }
        return $retour;
    }

}