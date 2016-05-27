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
            case 'omcomposed' :
                $children = $objet->getChildren();
                if (!empty($children)) {
                    switch ($objet->getObject()){
                        case "omtable":
                            if (empty($properties['loadDatas'])) {
                                $start = $objet->getStart();
                                if (empty($start)) $start = 0;
                                $length = $objet->getLength();
                                if (empty($length)) $length = 10;
                                if (is_array($length)) $length = $length[0];

                                $dataLines = $objet->getDataLines();
                                $tabArray  = [];
                                for ($i =0; $i < $length; $i++) {
                                    $tabArray[$i + 1] = $dataLines[$start + $i + 1];
                                }
                                $objet->table->setLines($tabArray);
                                $html->setVariable('table', $objet->table);

                                $objet->buildNavBar($start + 1, sizeof($dataLines), $this->sl);
                            }
                            break;
                    }
                    foreach ($children as $key => $child) {
                        $child = OObject::buildObject($child->getId());
                        $name = substr($child->getId(), strlen($properties['id']) + 1);
                        $html->setVariable($name, $child);
                    }
                    $html->setTemplate($template);
                    $html->setVariable('objet', $properties);
                }
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
