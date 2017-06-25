<?php

namespace GraphicObjectTemplating\View\Helper\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use GraphicObjectTemplating\View\Helper\GotAjax;

class GotAjaxFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        // $sl is instanceof ViewHelperManager, we need the real SL though
        $rsl = $sl->getServiceLocator();
        return new GotAjax($rsl);
    }
}