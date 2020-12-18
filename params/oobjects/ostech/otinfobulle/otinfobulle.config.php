<?php

use GraphicObjectTemplating\OObjects\OObject;
use GraphicObjectTemplating\OObjects\OSTech\OTInfoBulle;

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
