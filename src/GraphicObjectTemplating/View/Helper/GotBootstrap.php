<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 11:40
 */

namespace GraphicObjectTemplating\View\Helper;


use Zend\Config\Config;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class GotBootstrap extends AbstractHelper
{
    protected $sl;
    protected $viewManager;

    public function __construct($sl)
    {
        /** @var ServiceManager sl */
        $this->sl = $sl;
        $this->viewManager = $this->sl->get('ViewManager');
        return $this;
    }

    public function __invoke($widthBT)
    {
        if (is_numeric($widthBT)) {
            $class  = " col-xs-" . $widthBT . " ";
            $class .= " col-sm-" . $widthBT . " ";
            $class .= " col-md-" . $widthBT . " ";
            $class .= " col-lg-" . $widthBT . " ";
        } else {
            $widths = explode(":", $widthBT);
            $class = "";
            foreach ($widths as $width) {
                $media = strtoupper(substr($width, 0, 2));
                $larg = intval(substr($width, 2));

                switch ($media) {
                    case "WX":
                        $class .= " col-xs-" . $larg . " ";             break;
                    case "WS":
                        $class .= " col-sm-" . $larg . " ";             break;
                    case "WM":
                        $class .= " col-md-" . $larg . " ";             break;
                    case "WL":
                        $class .= " col-lg-" . $larg . " ";             break;
                    case "OX":
                        $class .= " col-xs-offset-" . $larg . " ";      break;
                    case "OS":
                        $class .= " col-sm-offset-" . $larg . " ";      break;
                    case "OM":
                        $class .= " col-md-offset-" . $larg . " ";      break;
                    case "OL":
                        $class .= " col-lg-offset-" . $larg . " ";      break;
                }
            }
        }
        return $class;
    }
}
