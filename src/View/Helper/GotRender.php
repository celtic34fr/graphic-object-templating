<?php

namespace GraphicObjectTemplating\View\Helper;

use GraphicObjectTemplating\Objects\ODContent;
use GraphicObjectTemplating\Objects\OObject;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class GotRender extends AbstractHelper
{
    /** @var  ServiceManager $sm */
    protected $sm;
    protected $gotServices;

    public function __construct($sm)
    {
        /** @var ServiceManager sm */
        $this->sm = $sm;
        $this->gotServices = $sm->get("graphic.object.templating.services");
        return $this;
    }

    public function __invoke($object)
    {
        $html = $this->gotServices->render($object);
        return $html;
    }
}
?>