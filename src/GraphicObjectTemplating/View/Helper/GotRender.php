<?php

namespace GraphicObjectTemplating\View\Helper;

use GraphicObjectTemplating\Objects\ODContent;
use GraphicObjectTemplating\Objects\OObject;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class GotRender extends AbstractHelper
{
    /** @var  ServiceManager $sl */
    protected $sl;
    protected $gotServices;

    public function __construct($sl)
    {
        /** @var ServiceManager sl */
        $this->sl = $sl;
        $this->gotServices = $sl->get("graphic.object.templating.services");
        return $this;
    }

    public function __invoke($object)
    {
        $html = $this->gotServices->render($object);
        return $html;
    }
}
?>
