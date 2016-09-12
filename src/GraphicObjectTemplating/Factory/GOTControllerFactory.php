<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 11/09/16
 * Time: 16:07
 */

namespace GraphicObjectTemplating\Factory;


use GraphicObjectTemplating\Controller\GOTController;
use GraphicObjectTemplating\Service\ControllerService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GOTControllerFactory implements FactoryInterface
{

    /**
     * Create service
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        $cs = new ControllerService($sl->getServiceLocator());
        return new GOTController($cs);
    }
}