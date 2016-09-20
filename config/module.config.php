<?php

namespace GraphicObjectTemplating;

use GraphicObjectTemplating\Controller\GOTController;
use GraphicObjectTemplating\Controller\Factory\GOTControllerFactory;
return array(

    'router' => array(
        'routes' => array(
            'got-callback' => [
                'type' => 'Literal',
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
            'GraphicObjectTemplating\Service\GotServices' => 'GraphicObjectTemplating\Service\Factory\GotServicesFactory',
			'graphic.object.templating.services'          => 'GraphicObjectTemplating\Service\Factory\GotServicesFactory'
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
