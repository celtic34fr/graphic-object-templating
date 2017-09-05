<?php
namespace GraphicObjectTemplating\Service;

use GraphicObjectTemplating\Objects\OObject;
use GraphicObjectTemplating\Objects\OSContainer;
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
                case 'oecontained':
                    $html->setTemplate($template);
                    $html->setVariable('objet', $properties);
                    break;
                case 'oscontainer':
                case 'ocobjects':
                case 'oecontainer':
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

    public function header($objets)
    {
        $view = new ViewModel();
        $cssScripts = [];
        $jsScripts  = [];
        $rscs       = "";
        if (!is_array($objets)) $objets = array(0 => $objets);

        foreach ($objets as $objet) {
            if ($objet != null) {
                $properties = $objet->getProperties();
                $rscs = (isset($properties['resources'])) ? $properties['resources'] : "";
                if (!empty($rscs) && ($rscs !== false)) {
                    $cssList = $rscs['css'];
                    $jsList  = $rscs['js'];
                    if (!empty($cssList)) {
                        foreach ($cssList as $item) {
                            if (!in_array($item, $cssScripts)) $cssScripts[] = 'graphicobjecttemplating/objects/'.$properties['typeObj'].'/'.$properties['object'].'/'.$item;
                        }
                    }
                    if (!empty($jsList)) {
                        foreach ($jsList as $item) {
                            if (!in_array($item, $jsScripts)) $jsScripts[] = 'graphicobjecttemplating/objects/'.$properties['typeObj'].'/'.$properties['object'].'/'.$item;
                        }
                    }
                }

                if ($objet instanceof OSContainer) {
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
            }
        }

        $view->setTemplate('graphic-object-templating/got/got-header.twig');
        $view->setVariable('scripts', array('css' => $cssScripts, 'js' => $jsScripts));
        $renduHtml = $this->_twigRender->render($view);
        return $renduHtml;
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
                $nomController = "GraphicObjectTemplating/Objects/ODContent/".$controller;
                break;
            case (substr($controller, 0, 2) == 'OS') :
                $nomController = "GraphicObjectTemplating/Objects/OSContainer/".$controller;
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

        if (!empty($rscs) && ($rscs !== false)) {
            $cssList = $rscs['css'];
            $jsList = $rscs['js'];
            if (!empty($cssList)) {
                foreach ($cssList as $item) {
                    if (!in_array($item, $cssScripts)) $cssScripts[] =  'graphicobjecttemplating/objects/'.$properties['typeObj'].'/'.$properties['object'].'/'.$item;
                }
            }
            if (!empty($jsList)) {
                foreach ($jsList as $item) {
                    if (!in_array($item, $jsScripts)) $jsScripts[] = 'graphicobjecttemplating/objects/'.$properties['typeObj'].'/'.$properties['object'].'/'.$item;
                }
            }
        }

        if ($objet instanceof OSContainer) {
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