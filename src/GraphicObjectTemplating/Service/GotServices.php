<?php
namespace GraphicObjectTemplating\Service;

use GraphicObjectTemplating\OObjects\OObject;
use GraphicObjectTemplating\OObjects\OSContainer;
use GraphicObjectTemplating\OObjects\OESContainer;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/*
 */

/**
 * Class GotServices
 * @package GraphicObjectTemplating\Service
 *
 * Service pour exécution des méthode utilises en services et viewHelper
 *
 * render($objetc) : rendu HTML d'un objet
 * header($object) : génération du bloc à insérezr dans <header></header> des resources CSS & JS utiles à GOT
 * bootstrapClass  : module restituant suivant le contenu de $widthBT, la chaîne de craractères codant les classes
 *                   Bootstrap Twitter à mettre dans la page, l'objet GOT
 * execAjax
 */
class GotServices
{
    private $_serviceManager;
    private $_twigRender;

    public function __construct($sm, $tr)
    {
        $this->_serviceManager = $sm;
        $this->_twigRender = $tr;
        return $this;
    }

    public function render($object)
    {
            if ($object != null) {
            $html       = new ViewModel();
            if (!($object instanceof OObject)) {
                $object = OObject::buildObject($object);
            }
            $properties = $object->getProperties();
            $template   = $properties['template'];
            $allow      = 'ALLOW';

            switch($properties['typeObj']) {
                case 'odcontained' :
                case 'ottools':
                case 'oedcontained':
                    $html->setTemplate($template);
                    $html->setVariable('objet', $properties);
                    break;
                case 'oscontainer':
                case 'ocobjects':
                case 'oescontainer':
                    $content  = "";
                    $children = $object->getChildren();
                    if (!empty($children)) {
                        foreach ($children as $key => $child) {
                            $child = OObject::buildObject($child->getId());

                            $rendu    = $this->render($child);
                            $content .= $rendu;
                        }
                    }
                    $html->setTemplate($template);
                    $html->setVariable('objet', $properties);
                    $html->setVariable('content', $content);
                    break;
            }
            $renduHtml = $this->_twigRender->render($html);
            return str_replace(array("\r\n", "\r", "\n"), "", $renduHtml);
        }
    }

    public function bootstrapClass($widthBT)
    {
        if (!is_array($widthBT)) {
            $obj = new OObject("obj");
            $obj->setWidthBT($widthBT);
            $widthBT = $obj->getWidthBT();
        }

        $class = "";
        foreach ($widthBT as $key => $value) {
            if (substr($key, 0, 1) == "w" && $value > 0) {
                $class  .= " col-".substr($key, 1)."-" . $value. " ";
            }

            if (substr($key, 0, 1) == "o" && $value > 0) {
                $class  .= " col-".substr($key, 1)."-offset-" . $value. " ";
            }

            if (substr($key, 0, 1) === 'h' && $value === true) {
                $class  .= " hidden-".substr($key, 1)."-" . $value. " ";
            }

            if (substr($key, 0, 1) === 'v' && $value === true) {
                $class  .= " visible-".substr($key, 1)."-" . $value. " ";
            }
        }
        return $class;
    }

    public function rscs($objects)
    {
        if ($objects != null) {
            if (!($objects instanceof OObject)) {
                $objects = OObject::buildObject($objects);
            }
            $cssScripts = $jsScripts = [];
            $rscs = "";
            if (!is_array($objects)) {
                $objects = array(0 => $objects);
            }

            foreach ($objects as $object) {
                if ($object != null) {
                    $properties = $object->getProperties();
                    $prefix = 'graphicobjecttemplating/oobjects/';
                    if (array_key_exists('extension', $properties) && $properties['extension']) {
                        $prefix = $properties['resources']['prefix'];
                    }
                    $rscs = (isset($properties['resources'])) ? $properties['resources'] : "";
                    if (!empty($rscs) && ($rscs !== false)) {
                        $cssList = $rscs['css'];
                        $jsList = $rscs['js'];
                        if (!empty($cssList)) {
                            foreach ($cssList as $item) {
                                if (!in_array($item, $cssScripts)) {
                                    $cssScripts[] = $prefix . $properties['typeObj'] . '/' . $properties['object'] . '/' . $item;
                                }
                            }
                        }
                        if (!empty($jsList)) {
                            foreach ($jsList as $item) {
                                if (!in_array($item, $jsScripts)) {
                                    $jsScripts[] = $prefix . $properties['typeObj'] . '/' . $properties['object'] . '/' . $item;
                                }
                            }
                        }
                    }

                    if ($object instanceof OSContainer || $object instanceof OESContainer) {
                        $children = $object->getChildren();
                        if (!empty($children)) {
                            foreach ($children as $child) {
                                $tmpArray = $this->headerChild($child);
                                foreach ($tmpArray['css'] as $css) {
                                    if (!in_array($css, $cssScripts)) {
                                        $cssScripts[] = $css;
                                    }
                                }
                                foreach ($tmpArray['js'] as $js) {
                                    if (!in_array($js, $jsScripts)) {
                                        $jsScripts[] = $js;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return ['cssScripts' => $cssScripts, 'jsScripts' => $jsScripts];
        }
        return false;
    }

    public function header($objets)
    {
        $view = new ViewModel();

        $scripts = $this->rscs($objets);

        if ($scripts) {
            $view->setTemplate('graphic-object-templating/got/got-header.twig');
            $view->setVariable('scripts', array('css' => $scripts['cssScripts'], 'js' => $scripts['jsScripts']));
            $renduHtml = $this->_twigRender->render($view);
            return $renduHtml;
        }
        return false;
    }

    public function execAjax($callback, $params = array())
    {
        $pos = strpos($callback, '/');
        $module = ucfirst(substr($callback, 0, $pos));
        $callback = substr($callback, $pos + 1);
        $pos = strpos($callback, '/');
        $controller = ucfirst(substr($callback, 0, $pos));
        $method = substr($callback, $pos + 1);

        switch (true) {
            case (strpos($controller, 'Controller')) :
                $nomController = $module."/Controller/".$controller."Controller";
                break;
            case (substr($controller, 0, 2) == 'OC') :
                $nomController = "GraphicObjectTemplating/OObjects/ODContent/".$controller;
                break;
            case (substr($controller, 0, 2) == 'OS') :
                $nomController = "GraphicObjectTemplating/OObjects/OSContainer/".$controller;
                break;
            default:
                $nomController = $module.'/'.$controller;
                break;
        }
        $nomController = str_replace("/", chr(92), $nomController);
        $object = new $nomController;

        $result = call_user_func_array(array($object, $method),
            array(
                'sl' => $this->get('ServiceManager'),
                $params
            ));

        /**
         * traitement du mode de restitution
         */
        if (!isset($params) || !isset($params['mode']) || empty($params['mode']))
        {
            if (!isset($params)) $params =[];
            $params['mode'] = 'std';
        }
        switch ($params['mode']) {
            case 'std':
                $data = new JsonModel($result);
                break;
            case 'raw':
                $data = $result[0]['html'];
        }


        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent($data->serialize());
        return $response;
    }



    private function headerChild($objet)
    {
        $cssScripts = [];
        $jsScripts  = [];
        $rscs = $objet->getResources();
        $properties = $objet->getProperties();
        $prefix = 'graphicobjecttemplating/oobjects/';
        if (array_key_exists('extension', $properties) && $properties['extension']) {
            $prefix = $properties['resources']['prefix'];
        }

        if (!empty($rscs) && ($rscs !== false)) {
            $cssList = $rscs['css'];
            $jsList = $rscs['js'];
            if (!empty($cssList)) {
                foreach ($cssList as $item) {
                    if (!in_array($item, $cssScripts)) $cssScripts[] =  $prefix.$properties['typeObj'].'/'.$properties['object'].'/'.$item;
                }
            }
            if (!empty($jsList)) {
                foreach ($jsList as $item) {
                    if (!in_array($item, $jsScripts)) $jsScripts[] = $prefix.$properties['typeObj'].'/'.$properties['object'].'/'.$item;
                }
            }
        }

        if ($objet instanceof OSContainer || $objet instanceof OESContainer) {
            $children = $objet->getChildren();
            if (!empty($children)) {
                foreach ($children as $child) {
                    $tmpArray = $this->headerChild($child);
                    foreach ($tmpArray['css'] as $css) {
                        if (!in_array($css, $cssScripts)) {
                            $cssScripts[] = $css;
                        }
                    }
                    foreach ($tmpArray['js'] as $js) {
                        if (!in_array($js, $jsScripts)) {
                            $jsScripts[] = $js;
                        }
                    }
                }
            }
        }

        return  array('css' => $cssScripts, 'js' => $jsScripts);
    }

}
