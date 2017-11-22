<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25/10/17
 * Time: 23:47
 */

namespace GraphicObjectTemplating\View\Helper;


use Zend\Mvc\Controller\Plugin\Url;
use Zend\ServiceManager\ServiceManager;

class GotUrl
{
    /** @var  ServiceManager $sm */
    protected $sm;

    public function __construct($sm)
    {
        /** @var ServiceManager sm */
        $this->sm = $sm;
        return $this;
    }

    public function __invoke($route, $params, $options)
    {
        /** @var Url $urlPlugin */
        $urlPlugin = $this->sm->get('ControllerPluginManager')->get('Url');
        $html = $urlPlugin->fromRoute($route,$params, $options);
        return $html;
    }
}