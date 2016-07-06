<?php
namespace GraphicObjectTemplating\Service;

use GraphicObjectTemplating\Objects\OObject;
use GraphicObjectTemplating\Objects\OSContainer;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/*
 * Service pour exécution des méthode utilises en services et viewHelper
 * 
 * render($objetc) : rendu HTML d'un objet
 * header($object) : génération du bloc à insérezr dans <header></header> des resources CSS & JS utiles à GOT
 * bootstrapClass  : module restituant suivant le contenu de $widthBT, la chaîne de craractères codant les classes
 *                   Bootstrap Twitter à mettre dans la page, l'objet GOT
 */
class GotServices
{
	private $_twigRender;

	public function __construct($tr)
	{
		$this->_twigRender = $tr;
		return $this;
	}
	
	public function render($object)
	{
		$html       = new ViewModel();
        $properties = $object->getProperties();
        $template   = $properties['template'];
        $allow      = 'ALLOW';

        switch($properties['typeObj']) {
            case 'odcontent' :
                $html->setTemplate($template);
                $html->setVariable('objet', $properties);
                break;
            case 'oscontainer':
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
		return $renduHtml;
	}

    public function bootstrapClass($widthBT)
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

    public function header($objets)
    {
        $view = new ViewModel();
        $cssScripts = [];
        $jsScripts  = [];
        $rscs       = "";
        if (!is_array($objets)) $objets = array(0 => $objets);

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
                        $tmpArray = $this->headerChild($child);
                        $cssScripts = array_merge($cssScripts, $tmpArray['css']);
                        $jsScripts  = array_merge($jsScripts,  $tmpArray['js']);
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
                'sl' => $this->getServiceLocator(),
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
                    $tmpArray = $this->headerChild($child);
                    $cssScripts = array_merge($cssScripts, $tmpArray['css']);
                    $jsScripts  = array_merge($jsScripts,  $tmpArray['js']);
                }
            }
        }

        return  array('css' => $cssScripts, 'js' => $jsScripts);
    }

}
