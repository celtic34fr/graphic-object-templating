<?php

namespace GraphicObjectTemplating\Controller;

use GraphicObjectTemplating\OObjects\ODContained;
use GraphicObjectTemplating\OObjects\OObject;
use GraphicObjectTemplating\OObjects\OSContainer\OSForm;
use GraphicObjectTemplating\Service\GotServices;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Model\ViewModel;

class GOTController extends AbstractActionController
{
    /** @var ServiceManager $serviceManager */
    private $serviceManager;

    /** @param ServiceManager $serviceManager */
    public function __construct($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function indexAction()
    {
        $view = new ViewModel();
        return $view;
    }

    public function updateObjectsAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) { // récupération des paramètres d'appel
            $paramsPost = $request->getPost()->toArray();
            $params     = $results = [];
            foreach ($paramsPost as $cle => $value) {
                // alimentation du tableau $params avec les paramètres d'appel
                $value = $this->trimQuote($value);
                $params[$cle] = $value;
            }

            if (!empty($params['callback']) && (null !== $params['callback'])) {
                $params['callback'] = explode(' ', $params['callback']);
                list($objClass, $objMethod) = $params['callback'];
                $object    = $this->buildObject($objClass, $params);
                
                // traitement en cas de formulaire
                if (strlen($params['form']) > 0) {
                    $params['form'] = $this->buildFormDatas(substr($params['form'], 1,  -2));
                    $objId = OObject::buildObject($params['id']);
                    $this->buildFormObject($objId, $params['form']);
                }
                // appel de la méthode de l'objet passée en paramètre
                $results = call_user_func_array([$object, $objMethod], [$this->serviceManager, $params]);
            }
            return $results;
        }
        return FALSE;
    }

    public function callbackAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        /** @var GotServices $gs */
        $gs      = $this->serviceManager->get('graphic.object.templating.services');
        
        if ($request->isPost()) { // récupération des paramètres d'appel
            $paramsPost = $request->getPost()->toArray();
            $params     = [];
            foreach ($paramsPost as $cle => $value) {
                // alimentation du tableau $params avec les paramètres d'appel
                $value = $this->trimQuote($value);
                $params[$cle] = $value;
            }

            if (!empty($params['callback']) && (null !== $params['callback'])) {
                $params['callback'] = explode(' ', $params['callback']);
                list($objClass, $objMethod) = $params['callback'];
                $object    = $this->buildObject($objClass, $params);
                
                // traitement en cas de formulaire
                if (strlen($params['form']) > 2) {
                    $params['form'] = $this->buildFormDatas(substr($params['form'], 1,  -2));
                    $objId = OObject::buildObject($params['id']);
                    $this->buildFormObject($objId, $params['form']);
                }
                // appel de la méthode de l'objet passée en paramètre
                $results = call_user_func_array([$object, $objMethod], [$this->serviceManager, $params]);
                $result  = [];
                $rscs    = [];
                $objects = [];
                foreach ($results as $rlst) {
                    $html       = !empty($rlst['code']) ? $rlst['code'] : $gs->render($rlst['idSource']);
                    $objects[]  = ['id'=>$rlst['idCible'], 'mode'=>$rlst['mode'], 'code'=>$html];
                    $rscs       = $gs->rscs($rlst['idSource']);
                }

                // traitement des ressources pour injection de fichiers sans doublons
                $rscsObjs = [];
                foreach ($rscs as $key => $files) {
                    foreach ($files as $file) {
                        if (!array_key_exists($file, $rscsObjs)) {
                            $rscsObjs[$file] = $key.'|'.$file;
                        }
                    }
                }
                foreach ($rscsObjs as $item) {
                    $id  = '';
                    $key = substr($item, 0, strpos($item, '|'));
                    if ($key == 'cssScripts') { $id = 'css'; }
                    if ($key == 'jsScripts')  { $id = 'js'; }
                    $result[]   = ['id'=>$id, 'mode'=>'rscs', 'code'=>substr($item, strpos($item, '|') + 1)];
                }

                $viewModel = (new ViewModel([ 'content' => [array_merge($result, $objects)], ]))->setTerminal(TRUE);
                return $viewModel;
            }
        }
        return false;
    }
    
    private function trimQuote($var, $char = "'") {
        if ($var[0] === $char) { $var = substr($var, 1); }
        if ($var[strlen($var) - 1] === $char) {
            $var = substr($var, 0, - 1);
        }
        return $var;
    }
    
    private function buildObject($objClass, $params) {
        if (NULL === $params) {
            $object = new $objClass;
        } else {
            if (isset($params['obj']) && $params['obj'] == 'OUI') {
                $object = OObject::buildObject($params['id']);
            } else {
                $object = new $objClass($params);
            }
        }
        return $object;
    }
    
    private function buildFormObject($objId, $formDatas) {
        if ($objId->getForm() == '') {
            return FALSE;
        }
        /** @var OSForm $objForm */
        $objForm = OObject::buildObject($objId->getForm());
        $objForm->setFormDatas($formDatas);
        return $objForm;
    }
    
    private function buildFormDatas($form) {
        $datas     = explode('|', $form);
        $formDatas = [];
        foreach ($datas as $data) {
            $data   = explode('§', $data);
            $idF    = '';
            $val    = '';
            foreach ($data as $item) {
                if (strpos($item, 'id=') !== false) {
                    $idF = substr($item, 3);
                } else {
                    $val = $this->trimQuote(substr($item, 6), '*');
                }
            }
            /** @var ODContained $obj */
            $obj = OObject::buildObject($idF);
            $obj->convertValue($val);
            $formDatas[$idF] = $obj->getConverted();
        }
        return $formDatas;
    }
}
