<?php

namespace GraphicObjectTemplating\Controller\Factory;

use GraphicObjectTemplating\Controller\GOTController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GOTControllerFactory implements FactoryInterface
{
    /**
     * Create service
     * @param ServiceLocatorInterface $serviceLocator
     * @return GOTController
     */
    public function createService(ServiceLocatorInterface $controllerLocator)
    {
        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerLocator->getServiceLocator();

        return new GOTController( $serviceLocator );
    }
}
