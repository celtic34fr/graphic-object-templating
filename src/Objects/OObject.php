<?php

namespace GraphicObjectTemplating\Objects;

use GraphicObjectTemplating\Objects\ODContained;
use Zend\Session\Container;

/**
 * Class OObject
 * @package Application\Objects
 *
 * properties   : tableau (objet Config()) stockant l'ensemble des attributs de l'objet
 *
 *  id           : identifiant unique de l'objet
 *  template     : chaîne de caractère donnant le nom et chemin d'accès au template de l'objet
 *  display      : indique la notion d'afficahge de l'objet
 *      none            : l'objet est invisible
 *      block           : affichage en mode bloc
 *      inline          : affichage en mode 'inline' (insersion de ligne)
 *      inline-block    : affiche en mode mixte inline & block
 *  style        : style spécifique à l'objet
 *  classes      : classe CSS affectée(s) à lobjet
 *  resources    : liste en 2 tableau des fichies CSS et JS utilies à l'objet
 *  aclReference : resource ACL associé à l'objet
 *
 * TU initProperties        :
 * TU mergeProperties       :
 * TU setProperties         :
 * TU getProperties         :
 * TU setId                 :
 * TU getId                 :
 * TU setName               :
 * TU getName               :
 * setTemplate              :
 * getTemplate              :
 * TU setDisplay            :
 * TU getDisplay            :
 * TU setStyle              :
 * TU addStyle              :
 * TU getStyle              :
 * setClasses               :
 * addClass                 :
 * getClasses               :
 * setInfoBulle             :
 * getInfoBulle             :
 * setInfoBulleParams       :
 * TU setWidthBT            :
 * TU getWidthBT            :
 * TU static existObject    :
 * TU static destroyObject  :
 * static buildObject       :
 * static clearObjects      :
 * getResources             :
 * addCssResource           :
 * getCssResource           :
 * setCssResource           :
 * addJsResource            :
 * getJsResource            :
 * setJsResource            :
 * setAclReference          :
 * getAclReference          :
 * setObject                :
 * getObject                :
 * setTypeObj               : permet d'affecter le type de l'objet
 * getTypeObj               : restitue le type de l'objet
 * setClassName             :
 * getClassName             :
 * TU enable                : active l'objet
 * TU disable               : désactive l'objet
 * TU getState              : restitue l'état de l'objet activé (true) / désactivé (false)
 * setErreur                : affecte un messa  ge d'erreur à l'objet
 * getErreur                : restitue le message d'erreur de l'objet (si lieu)
 * TU enaAutoCenter(w,h)    : active l'auto centrage pour l'affichage de l'objet
 * TU disAutoCenter         ; desactive l'auto centrage pour l'affichage de l'objet
 * TU getAutoCenter         : restitue sous forme d'un tableau des valeurs actuelles des paramètres de centrage automatique
 * getEvent($evt)           : pour les objects ayant le paramétrage d'évènement, restitution des paramètres
 * // addJsCode($nom, $code)
 * // setJsCodes(array $codes)
 * // getJsCode($nom)
 * // getJsCodes()
 * // rmJsCode($nom)
 * addMetaData($key, $value)
 * setMetaDatas(array $metaDatas)
 * getMetatDatas()
 */
class OObject
{

    const DISPLAY_NONE = 'none';
    const DISPLAY_BLOCK = 'block';
    const DISPLAY_INLINE = 'inline';
    const DISPLAY_INBLOCK = 'inline-block';

    const INFOBULLE_LEFT = 'left';
    const INFOBULLE_BOTTOM = 'bottom';
    const INFOBULLE_RIGHT = 'right';
    const INFOBULLE_TOP = 'top';

    const TYPE_TOOLTIP = 'tooltip';
    const TYPE_POPOVER = 'popover';

    const TRIGGER_CLICK = 'click';
    const TRIGGER_HOVER = 'hover';
    const TRIGGER_FOCUS = 'focus';
//    const TRIGGER_MANUAL = 'manual'; pas mis en oeuvre => question du déclenchement non réglé

    const STATE_ENABLE = true;
    const STATE_DISABLE = false;

    const BG_DANGER = 'FFEEEE';
    const BG_WARNING = 'FFDE89';
    const BG_INFO = '';
    const BG_SUCCESS = '';
    const BG_PRIMARY = '';

    const TX_DANGER = 'FF0000';
    const TX_WARNING = 'FF7719';
    const TX_INFO = '';
    const TX_SUCCESS = '';
    const TX_PRIMARY = '';

    protected $id;
    protected $properties;
    protected $name;
    protected $form;

    protected $const_display;
    protected $const_infoBulle;
    protected $const_type;
    protected $const_trigger;
    protected $const_state;

    public function __construct($id, $adrProperties = null)
    {
        $this->initProperties($id, $adrProperties);
        return $this;
    }

    public function initProperties($id, $arrayData = null)
    {
        $objProperties = [];
        if (!empty($arrayData)) {
            $path  = __DIR__ ;
            $path .= '/../../view/graphic-object-templating/' . trim($arrayData);
            $objProperties = include $path;
        }
        $objProperties['id'] = $id;
        $this->id = $id;
        $objProperties['name'] = $id;
        $this->name = $id;

        if (array_key_exists('typeObj', $objProperties)) {
            $templateName = 'graphic-object-templating/oobject/' . $objProperties['typeObj'];
            $templateName .= '/' . $objProperties['object'] . '/' . $objProperties['template'];
            $objProperties['template'] = $templateName;

            $objName = 'GraphicObjectTemplating/Objects/';
            $objName .= strtoupper(substr($objProperties['typeObj'], 0, 3));
            $objName .= strtolower(substr($objProperties['typeObj'], 3)) . '/';
            $objName .= strtoupper(substr($objProperties['object'], 0, 3));
            $objName .= strtolower(substr($objProperties['object'], 3));
            $objName = str_replace('/', chr(92), $objName);
            $objProperties['className'] = $objName;
        }

        /** ajout des attribut de base de chaque objet */
        $properties = include __DIR__ . '/../../view/graphic-object-templating/oobject/oobject.config.php';
        foreach ($objProperties as $key => $objProperty) {
            $properties[$key] = $objProperty;
        }

        $gotObjList = OObject::validateSession();
        $objects    = [];
        if ($gotObjList->offsetExists('objects')) {
            $objects = $gotObjList->offsetGet('objects');
        }
        // conservation des informations mise en session
        if (array_key_exists($id, $objects)) {
            $savProperties = unserialize($objects[$id]);
            foreach ($savProperties as $key => $property) {
                $properties[$key] = $property;
            }
        }
        $objects[$properties['id']] = serialize($properties);
        $gotObjList->offsetSet('objects', $objects);
        $this->properties = $properties;

        return $this;
    }

    public function mergeProperties($id, $arrayData)
    {
        $objProperties = $this->getProperties();
        if ($id === $objProperties['id']) {
            $properties = include(__DIR__ . '/../../view/graphic-object-templating/' . $arrayData);
            foreach ($objProperties as $key => $objProperty) {
                $properties[$key] = $objProperty;
            }
            $this->setProperties($properties);
        }
        return $this;
    }

    public function setProperties(array $arrayProperties)
    {
        $properties = $this->getProperties();
        if ($properties) {
            foreach ($arrayProperties as $key => $arrayProperty) {
                $properties[$key] = $arrayProperty;
            }
            $gotObjList = OObject::validateSession();
            $objects = $gotObjList->offsetGet('objects');
            $objects[$properties['id']] = serialize($properties);
            $gotObjList->offsetSet('objects', $objects);

            $this->properties = $properties;
            return $this;
        }
        return false;
    }

    public function getProperties()
    {
        if (null !== $this->id) {
            $id = $this->id;
            if (OObject::existObject($id)) {
                $gotObjList = OObject::validateSession();
                $objects = $gotObjList->offsetGet('objects');
                return unserialize($objects[$id], ['allowed_classes' => true]);
            }
        }
        return false;
    }

    public function setId($id)
    {
        $id = (string)$id;
        $properties = $this->getProperties();
        $properties['id'] = $id;
        if (empty($properties['name'])) { $properties['name'] = $id; }
        $this->setProperties($properties);
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        $properties = $this->getProperties();
        return array_key_exists('id', $properties) ? $properties['id'] : false;
    }

    public function setName($name)
    {
        $name = (string)$name;
        $properties = $this->getProperties();
        $properties['name'] = $name;
        $this->name = $name;
        $this->setProperties($properties);
        return $this;
    }

    public function getName()
    {
        if (!empty($this->name)) {
            $name = $this->name;
        } else {
            $properties = $this->getProperties();
            $name = array_key_exists('name', $properties) ? $properties['name'] : '';
            $this->name = $name;
        }
        return $name;
    }

    public function setTemplate($template)
    {
        $properties = $this->getProperties();
        $template = (string)$template;
        if (strpos($template, '/') === false) {
            $templateName = 'graphic-object-templating/oobject/' . $properties['typeObj'];
            $templateName .= '/' . $properties['object'] . '/' . $template;
        } else {
            $templateName = $template;
        }

        $properties['template'] = $templateName;
        $this->setProperties($properties);
        return $this;
    }

    public function getTemplate()
    {
        $properties = $this->getProperties();
        return array_key_exists('template', $properties) ? $properties['template'] : false;
    }

    public function setDisplay($display = OObject::DISPLAY_BLOCK)
    {
        $displays = $this->getDisplayConstants();
        if (!in_array($display, $displays, true)) { $display = OObject::DISPLAY_BLOCK; }
        $properties = $this->getProperties();
        $properties['display'] = $display;
        $this->setProperties($properties);
        return $this;
    }

    public function getDisplay()
    {
        $properties = $this->getProperties();
        return array_key_exists('display', $properties) ? $properties['display'] : false;
    }

    public function setStyle($style)
    {
        $style = (string)$style;
        $properties = $this->getProperties();
        $properties['style'] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function addStyle($addStyle)
    {
        $addStyle = (string)$addStyle;
        $properties = $this->getProperties();
        $style = '';
        if (isset($properties['style'])) { $style = $properties['style']; }
        if ($style[strlen($style) - 1] !== ';') { $style .= ';'; }
        $style .= $addStyle;
        $properties['style'] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getStyle()
    {
        $properties = $this->getProperties();
        return array_key_exists('style', $properties) ? $properties['style'] : false;
    }

    public function setClasses($classes)
    {
        $classes = (string)$classes;
        $properties = $this->getProperties();
        $properties['classes'] = $classes;
        $this->setProperties($properties);
        return $this;
    }

    public function addClass($addClass)
    {
        $addClass = (string)$addClass;
        $properties = $this->getProperties();
        $classes = $properties['classes'];
        if (!empty($classes) && $classes[strlen($classes) - 1] !== ' ') { $classes .= ' '; }
        $classes .= trim($addClass);
        $properties['classes'] = $classes;
        $this->setProperties($properties);
        return $this;
    }

    public function getClasses()
    {
        $properties = $this->getProperties();
        return (array_key_exists('classes', $properties) ? $properties['classes'] : false);
    }

    public function setInfoBulle($titre, $contenu = '', $type = self::TYPE_TOOLTIP, array $params = null)
    {
        $titre = (string)$titre;
        $contenu = (string)$contenu;
        $types = $this->getTypesConst();
        if (!in_array($type, $types, true)) { $type = self::TYPE_TOOLTIP; }
        $properties = $this->getProperties();

        if (!array_key_exists('infoBulle', $properties)) {
            $properties['infoBulle'] = [];
        }
        $properties['infoBulle']['title'] = $titre;
        $properties['infoBulle']['content'] = $contenu;
        $properties['infoBulle']['type'] = $type;
        $this->setProperties($properties);
        if (!empty($params)) {
            $this->setInfoBulleParams($params);
        }
        return $this;
    }

    public function getInfoBulle()
    {
        $properties = $this->getProperties();
        return (array_key_exists('infoBulle', $properties) ? $properties['infoBulle'] : false);
    }

    public function setInfoBulleParams(array $params = null)
    {
        $properties = $this->getProperties();

        if (!empty($params)) {
            if (!array_key_exists('infoBulle', $properties)) {
                $properties['infoBulle'] = [];
            }
            foreach ($params as $key => $param) {
                switch (strtoupper($key)) {
                    case 'PLACEMENT': // placement simple pas composé (TOP ou LEFT ou BOTTOM ou RIGHT)
                        $infoBulles = $this->getInfoBullesConst();
                        if (!in_array($param, $infoBulles, true)) {
                            $param = self::INFOBULLE_TOP;
                        }
                        $properties['infoBulle']['placement'] = $param;
                        break;
                    case 'TRIGGER': // mode de déclenchement
                        $triggers = $this->getTriggersConst();
                        if (!in_array($param, $triggers, true)) {
                            $param = self::TRIGGER_HOVER;
                        }
                        $properties['infoBulle']['trigger'] = $param;
                        break;
                    default:
                        $properties['infoBulle'][$key] = $param;
                        break;
                }
            }
        }
        $this->setProperties($properties);
        return $this;
    }

    public function setWidthBT($widthBT)
    {
        $wxs = 0;
        $oxs = 0;
        $wsm = 0;
        $osm = 0;
        $wmd = 0;
        $omd = 0;
        $wlg = 0;
        $olg = 0;

        switch (true) {
            case (is_numeric($widthBT)):
                $wxs = $widthBT;
                $oxs = 0;
                $wsm = $widthBT;
                $osm = 0;
                $wmd = $widthBT;
                $omd = 0;
                $wlg = $widthBT;
                $olg = 0;
                break;
            default:
                /** widthBT chaîne de caractères */
                $widthBT = explode(':', $widthBT);
                foreach ($widthBT as $item) {
                    $cle = strtoupper(substr($item, 0, 2));
                    $key = str_split($cle);
                    switch ($cle) {
                        case 'WX' :
                            $wxs = (int)substr($item, 2);
                            break;
                        case 'WS' :
                            $wsm = (int)substr($item, 2);
                            break;
                        case 'WM' :
                            $wmd = (int)substr($item, 2);
                            break;
                        case 'WL' :
                            $wlg = (int)substr($item, 2);
                            break;
                        case 'OX' :
                            $oxs = (int)substr($item, 2);
                            break;
                        case 'OS' :
                            $osm = (int)substr($item, 2);
                            break;
                        case 'OM' :
                            $omd = (int)substr($item, 2);
                            break;
                        case 'OL' :
                            $olg = (int)substr($item, 2);
                            break;
                        default:
                            if ($key[0] === 'W') {
                                $wxs = (int)substr($item, 1);
                                $wsm = (int)substr($item, 1);
                                $wmd = (int)substr($item, 1);
                                $wlg = (int)substr($item, 1);
                            }
                            if ($key[0] === 'O') {
                                $oxs = (int)substr($item, 1);
                                $osm = (int)substr($item, 1);
                                $omd = (int)substr($item, 1);
                                $olg = (int)substr($item, 1);
                            }
                    }
                }
        }
        $properties = $this->getProperties();
        $properties['widthBT'] = [];
        $properties['widthBT']['wxs'] = $wxs;
        $properties['widthBT']['wsm'] = $wsm;
        $properties['widthBT']['wmd'] = $wmd;
        $properties['widthBT']['wlg'] = $wlg;
        $properties['widthBT']['oxs'] = $oxs;
        $properties['widthBT']['osm'] = $osm;
        $properties['widthBT']['omd'] = $omd;
        $properties['widthBT']['olg'] = $olg;
        $this->setProperties($properties);
        return $this;
    }

    public function getWidthBT()
    {
        $properties = $this->getProperties();
        return (array_key_exists('widthBT', $properties) ? $properties['widthBT'] : false);
    }

    public function getResources()
    {
        $properties = $this->getProperties();
        return (array_key_exists('resources', $properties) ? $properties['resources'] : false);
    }

    public function addCssResource($cssResource)
    {
        $properties = $this->getProperties();
        if (!isset($properties['resources'])) { $properties['resources'] = []; }
        if (!isset($properties['resources']['css'])) { $properties['resources']['css'] = []; }

        switch (true) {
            case (is_string($cssResource)):
                $properties['resources']['css'][] = $cssResource;
                break;
            case (is_array($cssResource)):
                foreach ($cssResource as $item) {
                    if (!in_array($item, $properties['resources']['css'])) {
                        $properties['resources']['css'][] = $item;
                    }
                }
                break;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function getCssResource()
    {
        $properties = $this->getProperties();
        if (array_key_exists('resources', $properties)) {
            $tmp = $properties['resources'];
            return (array_key_exists('css', $tmp) ? $tmp['css'] : false);
        }
        return false;
    }

    public function setCssResource($cssResource)
    {
        $properties = $this->getProperties();
        if (!isset($properties['resources'])) { $properties['resources'] = []; }
        if (!isset($properties['resources']['css'])) { $properties['resources']['css'] = []; }

        switch (true) {
            case (is_string($cssResource)):
                $properties['resources']['css'][] = $cssResource;
                break;
            case (is_array($cssResource)):
                $properties['resources']['css'] = $cssResource;
                break;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function addJsResource($jsResource)
    {
        $properties = $this->getProperties();
        if (!isset($properties['resources'])) { $properties['resources'] = []; }
        if (!isset($properties['resources']['js'])) { $properties['resources']['js'] = []; }

        switch (true) {
            case (is_string($jsResource)):
                $properties['resources']['js'][] = $jsResource;
                break;
            case (is_array($jsResource)):
                foreach ($jsResource as $item) {
                    if (!in_array($item, $properties['resources']['js'])) {
                        $properties['resources']['js'][] = $item;
                    }
                }
                break;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function getJsResource()
    {
        $properties = $this->getProperties();
        if (array_key_exists('resources', $properties)) {
            $tmp = $properties['resources'];
            return (array_key_exists('js', $tmp) ? $tmp['js'] : false);
        }
        return false;
    }

    public function setJsResource($jsResource)
    {
        $properties = $this->getProperties();
        if (!isset($properties['resources'])) { $properties['resources'] = []; }
        if (!isset($properties['resources']['js'])) { $properties['resources']['js'] = []; }

        switch (true) {
            case (is_string($jsResource)):
                $properties['resources']['js'][] = $jsResource;
                break;
            case (is_array($jsResource)):
                $properties['resources']['js'] = $jsResource;
                break;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function setAclReference($aclReference)
    {
        $aclReference = (string)$aclReference;
        $properties = $this->getProperties();
        $properties['aclReference'] = $aclReference;
        $this->setProperties($properties);
        return $this;
    }

    public function getAclRefence()
    {
        $properties = $this->getProperties();
        return (array_key_exists('aclReference', $properties) ? $properties['aclReference'] : false);
    }

    public function setObject($object)
    {
        $object = (string)$object;
        $properties = $this->getProperties();
        $properties['object'] = $object;
        $this->setProperties($properties);
        return $this;
    }

    public function getObject()
    {
        $properties = $this->getProperties();
        return (array_key_exists('object', $properties) ? $properties['object'] : false);
    }

    public function setTypeObj($typeObj)
    {
        $typeObj = (string)$typeObj;
        $properties = $this->getProperties();
        $properties['typeObj'] = $typeObj;
        $this->setProperties($properties);
        return $this;
    }

    public function getTypeObj()
    {
        $properties = $this->getProperties();
        return (array_key_exists('typeObj', $properties) ? $properties['typeObj'] : false);
    }

    public function setClassName($className)
    {
        $className = (string)$className;
        $properties = $this->getProperties();
        $properties['className'] = $className;
        $this->setProperties($properties);
        return $this;
    }

    public function getClassName()
    {
        $properties = $this->getProperties();
        return (array_key_exists('className', $properties) ? $properties['className'] : false);
    }

    public function enable()
    {
        $properties = $this->getProperties();
        $properties['state'] = self::STATE_ENABLE;
        $this->setProperties($properties);
        return $this;
    }

    public function disable()
    {
        $properties = $this->getProperties();
        $properties['state'] = self::STATE_DISABLE;
        $this->setProperties($properties);
        return $this;
    }

    public function getState()
    {
        $properties = $this->getProperties();
        return ($properties['state'] === true);
    }

    public function setErreur($libel, $backgroundColor = 'FFEEEE', $color = 'FF0000')
    {
        $libel = (string)$libel;
        if (!empty($libel)) {
            $properties = $this->getProperties();
            if (!array_key_exists('erreur', $properties)) {
                $properties['erreur'] = [];
            }
            $properties['erreur']['libel'] = $libel;
            $properties['erreur']['backgroundColor'] = $backgroundColor;
            $properties['erreur']['color'] = $color;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function getErreur()
    {
        $properties = $this->getProperties();
        return (array_key_exists('erreur', $properties) ? $properties['erreur'] : false);
    }

    public function enaAutoCenter($width, $height)
    {
        $properties = $this->getProperties();
        $properties['autoCenter'] = true;
        $properties['autoCenterPx'] = $width;
        $properties['autoCenterPy'] = $height;
        $this->setProperties($properties);
        return $this;
    }

    public function disAutoCenter()
    {
        $properties = $this->getProperties();
        $properties['autoCenter'] = false;
        $this->setProperties($properties);
        return $this;
    }

    public function getAutoCenter()
    {
        $item = [];
        $properties = $this->getProperties();
        $item['autoCenter'] = $properties['autoCenter'];
        $item['autoCenterPx'] = $properties['autoCenterPx'];
        $item['autoCenterPy'] = $properties['autoCenterPy'];
        return $item;
    }

    public function setForm($form)
    {
        $form = (string)$form;
        $properties = $this->getProperties();
        $properties['form'] = $form;
        $this->form = $form;
        $this->setProperties($properties);
        return $this;
    }

    public function getForm()
    {
        if (!empty($this->form)) {
            $form = $this->form;
        } else {
            $properties = $this->getProperties();
            $form = array_key_exists('form', $properties) ? $properties['form'] : '';
            $this->setForm($form);
        }
        return $form;
    }

    public function getEvent($evt)
    {
        $properties = $this->getProperties();
        $ret        = [];
        if (array_key_exists('event', $properties)) {
            $events = $properties['event'];
            if (array_key_exists($evt, $events)) {
                $ret['class']     = $events[$evt]['class'];
                $ret['method']    = $events[$evt]['method'];
                $ret['stopEvent'] = ($events[$evt]['stopEvent'] === 'OUI');
            }
        }
        return $ret;
    }

    public function setStopEvent($event, $stopEvent = true)
    {
        $item = [];
        $item['id'] = $this->getId();
        $item['mode'] = 'event';
        $item['html'] = $event .'|'. ($stopEvent) ? 'OUI' : 'NON';
        return $item;
    }

    /*
    public function addJsCode($nom, $code)
    {
        $nom        = (string) $nom;
        $code       = (string) $code;
        $properties = $this->getProperties();
        $jsCode     = $properties['jsCode'];
        if (!array_key_exists($nom, $jsCode)) {
            $jsCode[$nom]           = $code;
            $properties['jsCode']   = $jsCode;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function setJsCodes(array $codes)
    {
        $properties             = $this->getProperties();
        $properties['jsCode']   = $codes;
        $this->setProperties($properties);
        return $this;
    }

    public function getJsCode($nom)
    {
        $nom        = (string) $nom;
        $properties = $this->getProperties();
        $jsCode     = $properties['jsCode'];
        if (array_key_exists($nom, $jsCode)) { return $jsCode[$nom]; }
        return false;
    }

    public function getJsCodes()
    {
        $properties = $this->getProperties();
        return (array_key_exists('jsCode', $properties) ? $properties['jsCode'] : false);
    }

    public function rmJsCode($nom)
    {
        $nom        = (string) $nom;
        $properties = $this->getProperties();
        $jsCode     = $properties['jsCode'];
        if (array_key_exists($nom, $jsCode)) {
            unset($jsCode[$nom]);
            $properties['jsCode'] = $jsCode;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }
    */

    public function addMetaData($key, $value)
    {
        $properties = $this->getProperties();
        $properties['metaDatas'][$key] = $value;
        $this->setProperties($properties);
        return $this;
    }

    public function setMetaDatas(array $metaDatas)
    {
        $properties = $this->getProperties();
        $properties['metaDatas'] = $metaDatas;
        $this->setProperties($properties);
        return $this;
    }

    public function getMetaDatas()
    {
        $properties = $this->getProperties();
        return (array_key_exists('metaDatas', $properties) ? $properties['metaDatas'] : false);
    }

    /*
     * méthode interne à la classe OObject
     */
    protected function getConstants()
    {
        $ref = new \ReflectionClass($this);
        return $ref->getConstants();
    }

    protected function getDisplayConstants()
    {
        $retour = [];
        if (empty($this->const_display)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'DISPLAY');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_display = $retour;
        } else {
            $retour = $this->const_display;
        }

        return $retour;
    }

    protected function getInfoBullesConst()
    {
        $retour = [];
        if (empty($this->const_infoBulle)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'INFOBULLE');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_infoBulle = $retour;
        } else {
            $retour = $this->const_infoBulle;
        }
        return $retour;
    }

    protected function getTypesConst()
    {
        $retour = [];
        if (empty($this->const_type)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'TYPE');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_type = $retour;
        } else {
            $retour = $this->const_type;
        }
        return $retour;
    }

    protected function getTriggersConst()
    {
        $retour = [];
        if (empty($this->const_trigger)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'TRIGGER');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_trigger = $retour;
        } else {
            $retour = $this->const_trigger;
        }
        return $retour;
    }

    protected function getStatesConst()
    {
        $retour = [];
        if (empty($this->const_state)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'STATE');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_state = $retour;
        } else {
            $retour = $this->const_state;
        }
        return $retour;
    }
    
    public function object_to_array($object) 
    {
        if (is_object($object)) {
            return array_map(__FUNCTION__, get_object_vars($object));
        } elseif (is_array($object)) {
            return array_map(__FUNCTION__, $object);
        }
        return $object;
    }

    
    /** méthodes statiques */
    
    static public function validateSession()
    {
        $now        = new \DateTime('now');
        $container  = new Container('gotObjList');
        $lastAccess = $container->offsetGet('lastAccess');
        if ($lastAccess) {
            $lastAccess = new \DateTime($lastAccess);
            $interval   = $lastAccess->diff($now);
            if ((int) $interval->format('%h') > 2) {
                $container->getManager()->getStorage()->clear('gotObjList');
                $container = new Container('gotObjList');
            }
        }
        $container->offsetSet('lastAccess', $now->format("Y-m-d H:i:s"));
        return $container;
    }

    static public function existObject($id)
    {
        if (!empty($id)) {
            $gotObjList = OObject::validateSession();
            if ($gotObjList->offsetExists('objects')) {
                $objects = $gotObjList->offsetGet('objects');
                if (array_key_exists($id, $objects)) {
                    return true;
                }
            }
        }
        return false;
    }

    static public function destroyObject($id)
    {
        if (OObject::existObject($id)) {
            $gotObjList = OObject::validateSession();
            $objects = $gotObjList->offsetGet('objects');
            unset($objects[$id]);
            $gotObjList->offsetSet('objects', $objects);
            return true;
        }
        return false;
    }

    static public function buildObject($id, $value = null)
    {
        if (OObject::existObject($id)) {
            $gotObjList = OObject::validateSession();
            $objects = $gotObjList->offsetGet('objects');

            $properties = unserialize($objects[$id], ['allowed_classes' => true]);
            /** @var OObject $obj */
            $obj = null;

            if (!empty($properties)) {
                $obj = new $properties['className']($properties['id']);
                $obj->setProperties($properties);
                if (!empty($value) && $obj instanceof ODContained) { $obj->setValue($value); }
                return $obj;
            }
            throw new \Exception("objet $id existant en session sans attributs");
        }
        return false;
    }

    static public function clearObjects()
    {
        $container  = OObject::validateSession();
        $container->offsetUnset('objects');
        return $container;
    }
}
