<?php

namespace GraphicObjectTemplating\View\Helper;

use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;
use GraphicObjectTemplating\Objects\OObject;

class GotProperties extends AbstractHelper
{
    protected $sm;
    protected $gotServices;

    public function __construct($sm)
    {
        /** @var ServiceManager sl */
        $this->sm = $sm;
        $this->gotServices = $sm->get("graphic.object.templating.services");
        return $this;
    }

    public function __invoke($object)
    {
        $obj = OObject::buildObject($object);
        return json_encode($obj->getProperties());
    }
}