<?php

namespace GraphicObjectTemplating;

use GraphicObjectTemplating\Controller\Factory\GOTControllerFactory;
use GraphicObjectTemplating\Controller\GOTController;
use GraphicObjectTemplating\Service\Factory\GotServicesFactory;
use GraphicObjectTemplating\Service\GotServices;
use GraphicObjectTemplating\Twig\Extension\ColorConverterTwigExtension;
use GraphicObjectTemplating\Twig\Extension\LayoutExtension;
use GraphicObjectTemplating\View\Helper\Factory\GotBootstrapFactory;
use GraphicObjectTemplating\View\Helper\Factory\GotHeaderFactory;
use GraphicObjectTemplating\View\Helper\Factory\GotRenderFactory;
use GraphicObjectTemplating\View\Helper\Factory\GotPropertiesFactory;
use GraphicObjectTemplating\View\Helper\GotBootstrap;
use GraphicObjectTemplating\View\Helper\GotHeader;
use GraphicObjectTemplating\View\Helper\GotRender;
use GraphicObjectTemplating\View\Helper\GotZendVersion;
use GraphicObjectTemplating\View\Helper\GotProperties;
use Zend\Router\Http\Literal;

return array(

    'router' => array(
        'routes' => array(
            'got-callback' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/got-callback',
                    'defaults' => [
                        'controller' => GOTController::class,
                        'action'     => 'callback',
                    ],
                ],
            ],
        ),
    ),
    
    'service_manager' => array(
		'factories' => array(
            GotServices::class                      => GotServicesFactory::class,
			'graphic.object.templating.services'    => GotServicesFactory::class,
		)
    ),

    'controllers' => array(
        'factories' => array(
           GOTController::class => GOTControllerFactory::class,
        ),
    ),

    'view_manager' => array(
        'template_map' => [
            'addons/macroLayout'      => __DIR__ . '/../view/addons/macroLayout.twig',
        ],
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => [
        'factories' => [
            GotRender::class     => GotRenderFactory::class,
            GotBootstrap::class  => GotBootstrapFactory::class,
            GotHeader::class     => GotHeaderFactory::class,
            GotProperties::class => GotPropertiesFactory::class,
            'zfVersion'    => function($sm) {
                return new GotZendVersion();
            }
        ],
        'aliases' => [
            'gotRender'     => GotRender::class,
            'gotBootstrap'  => GotBootstrap::class,
            'gotHeader'     => GotHeader::class,
            'gotProperties' => GotProperties::class
        ],
    ],
    'zfctwig' => [
        'extensions' => [
            'layout'            => LayoutExtension::class,
            'colorConverter'    => ColorConverterTwigExtension::class,
        ],
    ],
);
