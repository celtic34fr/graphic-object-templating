<?php

namespace GraphicObjectTemplating\View\Helper;

use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;

class GotBootstrap extends AbstractHelper
{
    protected $sm;
    protected $gotServices;

    public function __construct($sm)
    {
        /** @var ServiceManager $sm */
        $this->sm = $sm;
        $this->gotServices = $sm->get("graphic.object.templating.services");
        return $this;
    }

    public function __invoke($widthBT)
    {
        $class = $this->gotServices->bootstrapClass($widthBT);
        return $class;
    }
}
