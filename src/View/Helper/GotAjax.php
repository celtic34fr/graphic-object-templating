<?php

namespace GraphicObjectTemplating\View\Helper;

use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;

class GotAjax extends AbstractHelper
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

    public function __invoke($callback, $params = [])
    {
        $class = $this->gotServices->execAjax($callback, $params);
        return $class;
    }
}