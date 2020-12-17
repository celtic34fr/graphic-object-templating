<?php

namespace GraphicObjectTemplating;



use Laminas\ModuleManager\Feature\AutoloaderProviderInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\Feature\ViewHelperProviderInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\Session\Config\SessionConfig;
use Laminas\Session\Container;
use Laminas\Session\SessionManager;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface,
    ViewHelperProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $config = $e->getApplication()->getServiceManager()->get('Config');
        $paramsSession = $config['gotParameters']['sessionParms'];
        if (!empty($paramsSession)) {
            $this->initSession($paramsSession);
        }
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // Autoload all classes from namespace 'GraphicObjectTemplating' from '/module/GraphicObjectTemplating/src/GraphicObjectTemplating'
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                )
            )
        );
    }

    /*
     * impltentation de Session
     */
    public function initSession($config)
    {
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config);
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->start();
        Container::setDefaultManager($sessionManager);
    }

    public function getViewHelperConfig()
    {
        // TODO: Implement getViewHelperConfig() method.
    }
}