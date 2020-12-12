<?php

use GraphicObjectTemplating\OObjects\OSTech\OTInfoBulle;
use GraphicObjectTemplating\OObjects\OObject;


$infoBulle     	= [
	'setIB'         => false,
	'type'          => OTInfoBulle::IBTYPE_TOOLTIP,
	'animation'     => true,
	'delay_show'    => 500,
	'delay_hide'    => 100,
	'html'          => OObject::BOOLEAN_FALSE,
	'placement'     => OTInfoBulle::IBPLACEMENT_TOP,
	'title'         => null,
	'content'       => null,
	'trigger'       => OTInfoBulle::IBTRIGGER_HOVER,
];
$infoBulle_obj	= new OTInfoBulle($infoBulle);

return [
    'id'            => null,
    'name'          => null,
    'className'     => null,
    'display'       => OObject::DISPLAY_BLOCK,
    'object'        => null,
    'typeObj'       => null,
    'template'      => null,
    'widthBT'       => null,
    'lastAccess'    => null,
    'state'         => true,
    'classes'       => null,
    'width'         => null,
    'height'        => null,

    'autoCenter'    => false,
    'acPx'          => null,
    'acPy'          => null,

	'infoBulle' 	=> $infoBulle_obj
];