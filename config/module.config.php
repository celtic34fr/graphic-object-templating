<?php

namespace GraphicObjectTemplating;

return array(

    'view_manager' => array(
        'template_map' => array(
            'got/mainJs'             => __DIR__ . '/../view/graphic-object-templating/got/main.twig',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables'    => array(
            'GraphicObjectTemplating\Controller\GOT' => 'GraphicObjectTemplating\Controller\GOTController'
        )
    ),

    'service_manager' => array(
		'factories' => array(
            'GraphicObjectTemplating\Service\GotServices' => 'GraphicObjectTemplating\Service\Factory\GotServicesFactory',
			'graphic.object.templating.services'          => 'GraphicObjectTemplating\Service\Factory\GotServicesFactory'
		)
    ),

    'router' => array(
        'routes' => array(
            'got' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/got',
                    'defaults' => array(
                        'controller' => 'GraphicObjectTemplating\Controller\GOT',
                        'action'     => 'index',
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'callback' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/callback',
                            'defaults' => array(
                                'controller' => 'GraphicObjectTemplating\Controller\GOT',
                                'action'     => 'callback',
                            )
                        )
                    )
                )
            )
        ),
    ),

    'zfctwig' => array(
        'extensions' => array(
            \GraphicObjectTemplating\Twig\Extension\LayoutExtension::class,
            \GraphicObjectTemplating\Twig\Extension\ColorConverterTwigExtension::class,
        ),
    ),

);
