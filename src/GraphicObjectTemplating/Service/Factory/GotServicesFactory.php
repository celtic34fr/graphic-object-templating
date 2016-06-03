<?php
namespace GraphicObjectTemplating\Service\Factory;
 
use GraphicObjectTemplating\Service\GotServices;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
 
class GotServicesFactory implements FactoryInterface
{
    /**
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     * @return GotServices
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new GotServices($serviceLocator);
    } 
}
?>
