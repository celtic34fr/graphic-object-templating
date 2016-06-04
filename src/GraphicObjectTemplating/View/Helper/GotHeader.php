<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 11:40
 */

namespace GraphicObjectTemplating\View\Helper;

use GraphicObjectTemplating\Objects\OSContainer;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;

class GotHeader extends AbstractHelper
{
    protected $sl;
    protected $gotServices;

    public function __construct($sl)
    {
        /** @var ServiceManager sl */
        $this->sl = $sl;
        $this->gotServices = $sl->get("graphic.object.templating.services");
        return $this;
    }

    public function __invoke($objects)
    {
        $html = $this->gotServices->header($objects);
        return $html;
    }
}
