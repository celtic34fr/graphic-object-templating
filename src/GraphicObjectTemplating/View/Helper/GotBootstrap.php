<?php

namespace GraphicObjectTemplating\View\Helper;


use Zend\Config\Config;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class GotBootstrap extends AbstractHelper
{
    protected $sl;
    protected $gotServices;

    public function __construct($sl)
    {
        /** @var ServiceManager sl */
        $this->sl = $sl;
        $this->gotServices = $sl->get("graphic.object.templating.services");
        return $this;
    }

    public function __invoke($widthBT)
    {
        $class = $this->gotServices->bootstrapClass($widthBT);
        return $class;
    }
}
