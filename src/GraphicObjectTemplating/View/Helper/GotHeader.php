<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 11:40
 */

namespace GraphicObjectTemplating\View\Helper;

use GraphicObjectTemplating\Objects\OObject;
use GraphicObjectTemplating\Objects\OSContainer;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class GotHeader extends AbstractHelper
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

    public function __invoke($objets)
    {
        if (!is_array($objets)) $objets = array(0 => $objets);
        $cssScripts = [];
        $jsScripts  = [];
        $rscs       = "";
        foreach ($objets as $objet) {
            $properties = $objet->getProperties();
            $rscs = $properties['resources'];
            if (!empty($rscs) && ($rscs !== false)) {
                $cssList = $rscs['css'];
                $jsList  = $rscs['js'];
                if (!empty($cssList)) {
                    foreach ($cssList as $item) {
                        if (!in_array($item, $cssScripts)) $cssScripts[] = 'graphicobjecttemplating/'.$item;
                    }
                }
                if (!empty($jsList)) {
                    foreach ($jsList as $item) {
                        if (!in_array($item, $jsScripts)) $jsScripts[] = 'graphicobjecttemplating/'.$item;
                    }
                }
            }

            if ($objet instanceof OSContainer) {
                $children = $objet->getChildren();
                if (!empty($children)) {
                    foreach ($children as $child) {
                        $tmpArray = self::gotHeaderChild($child);
                        $cssScripts = array_merge($cssScripts, $tmpArray['css']);
                        $jsScripts  = array_merge($jsScripts,  $tmpArray['js']);
                    }
                }
            }
        }

        $view = new ViewModel();
        $view->setTemplate('graphic-object-templating/got/got-header.twig');
        $view->setVariable('scripts', array('css' => $cssScripts, 'js' => $jsScripts));
        $renduHtml = $this->sl->get('ZfcTwigRenderer')->render($view);
        return $renduHtml;
    }

    private function gotHeaderChild($objet)
    {
        $cssScripts = [];
        $jsScripts  = [];
        $rscs = $objet->getResources();

        if (!empty($rscs) && ($rscs !== false)) {
            $cssList = $rscs['css'];
            $jsList = $rscs['js'];
            if (!empty($cssList)) {
                foreach ($cssList as $item) {
                    if (!in_array($item, $cssScripts)) $cssScripts[] = 'graphicobjecttemplating/' . $item;
                }
            }
            if (!empty($jsList)) {
                foreach ($jsList as $item) {
                    if (!in_array($item, $jsScripts)) $jsScripts[] = 'graphicobjecttemplating/' . $item;
                }
            }
        }

        if ($objet instanceof OSContainer) {
            $children = $objet->getChildren();
            if (!empty($children)) {
                foreach ($children as $child) {
                    $tmpArray = self::gotHeaderChild($child);
                    $cssScripts = array_merge($cssScripts, $tmpArray['css']);
                    $jsScripts  = array_merge($jsScripts,  $tmpArray['js']);
                }
            }
        }

        return  array('css' => $cssScripts, 'js' => $jsScripts);
    }
}
