<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 15:53
 */

namespace GraphicObjectTemplating\View\Helper\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use GraphicObjectTemplating\View\Helper\GotBootstrap;

class GotBootstrapFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        // $sl is instanceof ViewHelperManager, we need the real SL though
        $rsl = $sl->getServiceLocator();
        return new GotBootstrap($rsl);
    }
}