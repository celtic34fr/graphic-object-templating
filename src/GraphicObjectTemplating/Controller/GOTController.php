<?php

namespace GraphicObjectTemplating\Controller;

use GraphicObjectTemplating\Objects\ODContained;
use GraphicObjectTemplating\Objects\OObject;
use GraphicObjectTemplating\Objects\OSContainer\OSForm;
use Zend\Http\Request;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class GOTController extends AbstractActionController
{
    /**
     * @var array
     */
    private $serviceManager;

    /**
     * @param $applicationDetails
     */
    public function __construct($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function indexAction()
    {
        $view = new ViewModel();
        return $view;
    }

    public function callbackAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        $id = null;
        if ($request->isPost()) {
            $paramsPost = $request->getPost()->toArray();
            $params     = [];
            foreach ($paramsPost as $cle => $value) {
                if ($value[0] === "'") { $value = substr($value, 1); }
                if ($value[strlen($value) - 1] === "'") {
                    $value = substr($value, 0, - 1);
                }
                $$cle = $value;
                $params[$cle] = $value;
            }

            if (!empty($callback) && (null !== $callback)) {
                $objClass  = substr($callback, 0, strpos($callback, ' '));
                $objMethod = substr($callback, strpos($callback, ' ') + 1);

                if (null !== $params) {
                    if (isset($params['obj']) && $params['obj'] == 'OUI') {
                        $object = OObject::buildObject($params['id']);
                    } else {
                        $object = new $objClass($params);
                    }
                } else {
                    $object = new $objClass;
                }
                // traitement en cas de formulaire
                if (!empty($form) && null !== $form) {
                    $formDatas = [];
                    $form      = substr($form, 1,  -2);
                    $datas     = explode('|', $form);

                    foreach ($datas as $data) {
                        if (!empty($data)) {
                            $data                = explode('§', $data);
                            foreach ($data as $item) {
                                switch (true) {
                                    case (strpos($item, 'id=') !== false):
                                        $idF = substr($item, 3);
                                        break;
                                    case (strpos($item, 'value=') !== false):
                                        $val = substr($item, 6);
                                        if ($val[0] === '*') { $val = substr($val, 1); }
                                        if ((strlen($val) > 1 && $val[strlen($val) - 1] === '*') || ($val === '*')) {
                                            $val = substr($val, 0,  -1);
                                            break;
                                        }
                                }
                                if (isset($idF, $val)) {
                                    /** @var ODContained $obj */
                                    $obj = OObject::buildObject($idF);
                                    $obj->convertValue($val);
                                    $val = $obj->getConverted();
                                    $formDatas[$idF] = $val;
                                    unset($idF);
                                    unset($val);
                                }
                            }
                        }
                    }
                    $params['form'] = $formDatas;
                    if (null !== $id) { // alimentation objet OSForm si existe
                        $objId = OObject::buildObject($id);
                        if (!empty($objId->getForm()) && OObject::existObject($objId->getForm())) {
                            /** @var OSForm $objForm */
                            $objForm = OObject::buildObject($objId->getForm());
                            $objForm->setFormDatas($formDatas);
                        }
                    }
                }

                $result = call_user_func_array([$object, $objMethod], [$this->serviceManager, $params]);

                $viewModel = new ViewModel([
                    'content' => $result,
                ]);
                $viewModel->setTerminal(true);
                return $viewModel;
            }
        }
        return false;
    }
}
