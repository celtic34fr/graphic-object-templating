<?php

namespace GraphicObjectTemplating\View\Helper;

use GraphicObjectTemplating\Objects\OSContainer;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;

class GotHeader extends AbstractHelper
{
    protected $sm;
    protected $gotServices;

    public function __construct($sm)
    {
        /** @var ServiceManager sm */
        $this->sm = $sm;
        $this->gotServices = $sm->get("graphic.object.templating.services");
        return $this;
    }

    public function __invoke($objects)
    {
        $html = $this->gotServices->header($objects);
        return $html;
    }
}
