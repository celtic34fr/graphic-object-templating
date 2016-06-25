<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 21/06/16
 * Time: 14:27
 */

namespace GraphicObjectTemplating\View\Helper;

use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;

class GotAjax extends AbstractHelper
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

    public function __invoke($callback, $params = [])
    {
        $class = $this->gotServices->execAjax($callback, $params);
        return $class;
    }
}