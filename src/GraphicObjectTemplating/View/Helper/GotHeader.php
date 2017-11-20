<?php

namespace GraphicObjectTemplating\View\Helper;

use GraphicObjectTemplating\Service\GotServices;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;

class GotHeader extends AbstractHelper
{
    /** @var  ServiceManager $sm */
    protected $sm;
    /** @var  GotServices $gotServices */
    protected $gotServices;

    public function __construct(ServiceManager $sm)
    {
        $this->sm = $sm;
        $this->gotServices = $sm->get("graphic.object.templating.services");
        return $this;
    }

    public function __invoke($objects)
    {
        $html = $this->gotServices->header($objects);
        return $html;
    }
}
