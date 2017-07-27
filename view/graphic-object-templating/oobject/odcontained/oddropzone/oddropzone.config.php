<?php
return array(
    'object'     => 'oddropzone',
    'typeObj'    => 'odcontained',
    'template'   => 'oddropzone.twig',
    'filters'    => array(),
    'multiple'   => false,
    'maxFiles'   => "1",
    'loadFiles'  => array(),
    'servFiles'  => array(),

    'resources' => array(
        'css' => array( 'dropzone.css' => 'css/dropzone.css' ),
        'js'  => array( 'dropzone.js' => 'js/dropzone.js' ),
        'img' => array(
			'spritemap.png' => 'img/spritemap.png',
			'spritemap@2x.png' => 'img/spritemap@2x.png',
        )
    )
);