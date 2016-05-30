<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 11:40
 */

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
    protected $viewManager;

    public function __construct($sl)
    {
        /** @var ServiceManager sl */
        $this->sl = $sl;
        $this->viewManager = $this->sl->get('ViewManager');
        return $this;
    }

    public function __invoke($objet)
    {
        $html       = new ViewModel();
        $properties = $objet->getProperties();
        $template   = $properties['template'];
        $allow      = 'ALLOW';

        switch($properties['typeObj']) {
            case 'odcontent' :
                $html->setTemplate($template);
                $html->setVariable('objet', $properties);
                break;
            case 'oscontainer':
                $content  = "";
                $children = $objet->getChildren();
                if (!empty($children)) {
                    foreach ($children as $key => $child) {
                        $child = OObject::buildObject($child->getId());

                        $rendu    = self::__invoke($child);
                        $content .= $rendu;
                    }
                }
                $html->setTemplate($template);
                $html->setVariable('objet', $properties);
                $html->setVariable('content', $content);
                break;
        }
        $renduHtml = $this->sl->get('ZfcTwigRenderer')->render($html);
        return $renduHtml;
    }
}
