<?php

namespace GraphicObjectTemplating;

use GraphicObjectTemplating\Controller\GOTController;
use GraphicObjectTemplating\Controller\Factory\GOTControllerFactory;
use GraphicObjectTemplating\Service\Factory\GotServicesFactory;
use GraphicObjectTemplating\Service\GotServices;
use Zend\Mvc\Router\Http\Literal;

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
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'zfctwig' => array(
        'extensions' => array(
            \GraphicObjectTemplating\Twig\Extension\LayoutExtension::class,
            \GraphicObjectTemplating\Twig\Extension\ColorConverterTwigExtension::class,
        ),
    ),

);
