<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 10:38
 */

namespace GraphicObjectTemplating\Objects;

use Zend\Session\Container;

/**
 * Class OObject
 * @package GraphicObjectTemplating\Objects
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
 * initProperties       :
 * mergeProperties      :
 * setProperties        !
 * getProiperties       :
 * setId                :
 * getId                :
 * setName              :
 * getName              :
 * setTemplate          :
 * getTemplate          :
 * setDisplay           :
 * getDisplay           :
 * setStyle             :
 * addStyle             :
 * getStyle             :
 * setClasses           :
 * addClass             :
 * getClasses           :
 * setInfoBulle         :
 * getInfoBulle         :
 * setInfoBulleParams   :
 * setWidthBT           :
 * getWidthBT           :
 * static buildObject   :
 * getResources         :
 * addCssResource       :
 * getCssResource       :
 * setCssResource       :
 * addJsResource        :
 * getJsResource        :
 * setJsResource        :
 * setAclReference      :
 * getAclReference      :
 * setObject            :
 * getObject            :
 * setTypeObj           : permet d'affecter le type de l'objet
 * getTypeObj           : restitue le type de l'objet
 * setClassName         :
 * getClassName         :
 * enable               : active l'objet
 * disable              : dactive l'objet
 * getState             :
 * setErreur            : affecte un messa  ge d'erreur à l'objet
 * getErreur            : restitue le message d'erreur de l'objet (si lieu)
 * enaAutoCenter(w,h)   : active l'auto centrage pour l'affichage de l'objet
 * disAutoCenter        ; desactive l'auto centrage pour l'affichage de l'objet
 */
class OObject
{

    const DISPLAY_NONE    = 'none';
    const DISPLAY_BLOCK   = 'block';
    const DISPLAY_INLINE  = 'inline';
    const DISPLAY_INBLOCK = 'inline-block';

    const INFOBULLE_LEFT    = "left";
    const INFOBULLE_BOTTOM  = "bottom";
    const INFOBULLE_RIGHT   = "right";
    const INFOBULLE_TOP     = "top";

    const TYPE_TOOLTIP = 'tooltip';
    const TYPE_POPOVER = 'popover';
    
    const TRIGGER_CLICK = 'click';
    const TRIGGER_HOVER = 'hover';
    const TRIGGER_FOCUS = 'focus';
//    const TRIGGER_MANUAL = 'manual'; pas mis en oeuvre => question du déclenchement non réglé

    const STATE_ENABLE  = true;
    const STATE_DISABLE = false;

    const BG_DANGER  = "FFEEEE";
    const BG_WARNING = "FFDE89";
    const BG_INFO    = "";
    const BG_SUCCESS = "";
    const BG_PRIMARY = "";

    const TX_DANGER  = "FF0000";
    const TX_WARNING = "FF7719";
    const TX_INFO    = "";
    const TX_SUCCESS = "";
    const TX_PRIMARY = "";

    protected $id;
    protected $properties;

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
        $properties = [];
        if (!empty($arrayData)) $properties = include(__DIR__ ."/../../../view/graphic-object-templating/" .trim($arrayData));
        $properties['id']   = $id;
        $this->id           = $id;
        $properties['name'] = $id;
        $this->name         = $id;

        if (array_key_exists('typeObj', $properties)) {
            $templateName  = 'graphic-object-templating/oobject/'.$properties['typeObj'];
            $templateName .= '/'.$properties['object'].'/'.$properties['template'];
            $properties['template'] = $templateName;

            $objName = "GraphicObjectTemplating/Objects/";
            $objName .= strtoupper(substr($properties['typeObj'], 0, 3));
            $objName .= strtolower(substr($properties['typeObj'], 3)).'/';
            $objName .= strtoupper(substr($properties['object'], 0, 3));
            $objName .= strtolower(substr($properties['object'], 3));
            $objName = str_replace("/", chr(92), $objName);
            $properties['className'] = $objName;
        }

        /** ajout des attribut de base de chaque objet */
        $objProperties = include(__DIR__ ."/../../../view/graphic-object-templating/oobject/oobject.config.phtml");
        $properties    = array_merge($objProperties, $properties);

        $session = new Container($id);
        $session->properties = serialize($properties);

        $this->properties = $properties;
        return $this;
    }

    public function mergeProperties($id, $arrayData)
    {
        $properties = $this->getProperties();
        if ($id == $properties['id']) {
            $nProperties = include(__DIR__ ."/../../../view/graphic-object-templating/" .$arrayData);
            $properties = array_merge($properties, $nProperties);
            $this->setProperties($properties);
        }
        return $this;
    }

    public function setProperties(array $arrayProperties)
    {
        $properties = $this->getProperties();
        foreach ($arrayProperties as $key => $arrayProperty) {
            $properties[$key] = $arrayProperty;
        }

        $session = new Container($properties['id']);
        $session->properties = serialize($properties);

        $this->properties    = $properties;
        return $this;
    }

    public function getProperties() {
        if (isset($this->properties)) {
            $id = $this->properties['id'];
            $session = new Container($id);
            return unserialize($session->properties);
        }
        return false;
    }

    public function setId($id)
    {
        $id = (string) $id;
        $properties = $this->getProperties();
        $properties['id'] = $id;
        if (empty($properties['name'])) $properties['name'] = $id;
        $this->setProperties($properties);
        return $this;
    }

    public function getId()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('id', $properties)) ? $properties['id'] : false);
    }

    public function setName($name)
    {
        $name = (string) $name;
        $properties = $this->getProperties();
        $properties['name'] = $name;
        $this->setProperties($properties);
        return $this;
    }

    public function getName()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('name', $properties)) ? $properties['name'] : false);
    }

    public function setTemplate($template)
    {
        $properties = $this->getProperties();
        $template      = (string) $template;
        $templateName  = 'graphic-object-templating/oobject/'.$properties['typeObj'];
        $templateName .= '/'.$properties['object'].'/'.$template;

        $properties['template'] = $templateName;
        $this->setProperties($properties);
        return $this;
    }

    public function getTemplate()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('template', $properties)) ? $properties['template'] : false);
    }

    public function setDisplay($display = OObject::DISPLAY_BLOCK)
    {
        $displays = $this->getDisplayConstants();
        if (!in_array($display, $displays)) $display = OObject::DISPLAY_BLOCK;

        $properties            = $this->getProperties();
        $properties['display'] = $display;
        $this->setProperties($properties);
        return $this;
    }

    public function getDisplay()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('display', $properties)) ? $properties['display'] : false);
    }

    public function setStyle($style)
    {
        $style               = (string) $style;
        $properties          = $this->getProperties();
        $properties['style'] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function addStyle($addStyle)
    {
        $addStyle            = (string) $addStyle;
        $properties          = $this->getProperties();
        $style               = "";
        if (isset($properties['style']))
			$style               = $properties['style'];
        if (substr($style, strlen($style) - 1, 1) !=";") $style .= ';';
        $style              .= $addStyle;
        $properties['style'] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getStyle()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('style', $properties)) ? $properties['style'] : false);
    }

    public function setClasses($classes)
    {
        $classes               = (string) $classes;
        $properties            = $this->getProperties();
        $properties['classes'] = $classes;
        $this->setProperties($properties);
        return $this;
    }

    public function addClass($addClass)
    {
        $addClass              = (string) $addClass;
        $properties            = $this->getProperties();
        $classes               = $properties['classes'];
        if (substr($classes, strlen($classes) - 1, 1) !=" ") $classes .= ' ';
        $classes              .= trim($addClass);
        $properties['classes'] = $classes;
        $this->setProperties($properties);
        return $this;
    }

    public function getClasses()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('classes', $properties)) ? $properties['classes'] : false);
    }

    public function setInfoBulle($titre, $contenu = "", $type = self::TYPE_TOOLTIP, array $params = null)
    {
        $titre                   = (string) $titre;
        $contenu                 = (string) $contenu;
        $types                   = $this->getTypesConst();
        if (!in_array($type, $types)) $type = self::TYPE_TOOLTIP;
        $properties              = $this->getProperties();
        
        if (!array_key_exists('infoBulle', $properties)) $properties['infoBulle'] = [];
        $properties['infoBulle']['title']   = $titre;
        $properties['infoBulle']['content'] = $contenu;
        $properties['infoBulle']['type']    = $type;
        $this->setProperties($properties);
        if (!empty($params)) {
            $this->setInfoBulleParams($params);
        }
        
        return $this;
    }

    public function getInfoBulle()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('infoBulle', $properties)) ? $properties['infoBulle'] : false);
    }

    public function setInfoBulleParams(array $params = null)
    {
        $properties = $this->getProperties();

        if (!empty($params)){
            if (!array_key_exists('infoBulle', $properties)) $properties['infoBulle'] = [];
            foreach ($params as $key => $param) {
                switch (strtoupper($key)) {
                    case 'PLACEMENT': // placement simple pas composé (TOP ou LEFT ou BOTTOM ou RIGHT)
                        $infoBulles = $this->getInfoBullesConst();
                        if (!in_array($param, $infoBulles)) $param = self::INFOBULLE_TOP;
                        $properties['infoBulle']['placement'] = $param;
                        break;
                    case 'TRIGGER': // mode de déclenchement
                        $triggers = $this->getTriggersConst();
                        if (!in_array($param, $triggers)) $param = self::TRIGGER_HOVER;
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
        $wxs = 0; $oxs = 0;
        $wsm = 0; $osm = 0;
        $wmd = 0; $omd = 0;
        $wlg = 0; $olg = 0;

        switch (true) {
            case (is_numeric($widthBT)):
                $wxs = $widthBT; $oxs = 0;
                $wsm = $widthBT; $osm = 0;
                $wmd = $widthBT; $omd = 0;
                $wlg = $widthBT; $olg = 0;
                break;
            default:
                /** widthBT chaîne de caractères */
                $widthBT = explode(":", $widthBT);
                foreach ($widthBT as $item) {
                    $key = strtoupper(substr($item, 0, 2));
                    switch ($key) {
                        case "WX" : $wxs = intval(substr($item,2)); break;
                        case "WS" : $wsm = intval(substr($item,2)); break;
                        case "WM" : $wmd = intval(substr($item,2)); break;
                        case "WL" : $wlg = intval(substr($item,2)); break;
                        case "OX" : $oxs = intval(substr($item,2)); break;
                        case "OS" : $osm = intval(substr($item,2)); break;
                        case "OM" : $omd = intval(substr($item,2)); break;
                        case "OL" : $olg = intval(substr($item,2)); break;
                        default:
                            if (substr($key,0,1) == "W") {
                                $wxs = intval(substr($item,1));
                                $wsm = intval(substr($item,1));
                                $wmd = intval(substr($item,1));
                                $wlg = intval(substr($item,1));
                            }
                            if (substr($key,0,1) == "O") {
                                $oxs = intval(substr($item,1));
                                $osm = intval(substr($item,1));
                                $omd = intval(substr($item,1));
                                $olg = intval(substr($item,1));
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
        return ((array_key_exists('widthBT', $properties)) ? $properties['widthBT'] : false);
    }

    static public function buildObject($id, $value = null)
    {
        $session = new Container($id);
        $properties = unserialize($session->properties);

        if (!empty($properties)) {
            if ($properties['typeObj'] == "omcomposed") {
                $children = $properties['children'];
                $propertiesChild = [];
                foreach ($children as $idObj => $child) {
                    $objet = OObject::buildObject($idObj);
                    $propertiesChild[$idObj] = $objet->getProperties();
                }
                $obj = new $properties['className']($properties['id']);
                foreach ($propertiesChild as $idObj => $propertyChild) {
                    $name = $propertyChild['name'];
                    $obj->$name->setProperties($propertyChild);
                }
            } else {
                $obj = new $properties['className']($properties['id']);
            }
            $obj->setProperties($properties);
            if (!empty($value)) $obj->setValue($value);
            return $obj;
        } else {
            throw new \Exception("objet $id inexistant en session");
        }
    }

    public function getResources()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('resources', $properties)) ? $properties['resources'] : false);
    }

    public function addCssResource($cssResource)
    {
        $properties = $this->getProperties();
        if (!isset($properties['resources'])) $properties['resources'] = [];
        if (!isset($properties['resources']['css'])) $properties['resources']['css'] = [];

        switch(true){
            case (is_string($cssResource)):
                $properties['resources']['css'][] = $cssResource;
                break;
            case (is_array($cssResource)):
                $properties['resources']['css'] = array_merge($properties['resources']['css'], $cssResource);
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
            return ((array_key_exists('css', $tmp)) ? $tmp['css'] : false);
        }
        return false;
    }

    public function setCssResource($cssResource)
    {
        $properties = $this->getProperties();
        if (!isset($properties['resources'])) $properties['resources'] = [];
        if (!isset($properties['resources']['css'])) $properties['resources']['css'] = [];
        $properties['resources']['css'] = [];

        switch(true){
            case (is_string($cssResource)):
                $properties['resources']['css'][] = $cssResource;
                break;
            case (is_array($cssResource)):
                $properties['resources']['css'] =  $cssResource;
                break;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function addJsResource($jsResource)
    {
        $properties = $this->getProperties();
        if (!isset($properties['resources'])) $properties['resources'] = [];
        if (!isset($properties['resources']['js'])) $properties['resources']['js'] = [];

        switch(true){
            case (is_string($jsResource)):
                $properties['resources']['js'][] = $jsResource;
                break;
            case (is_array($jsResource)):
                $properties['resources']['js'] = array_merge($properties['resources']['js'], $jsResource);
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
            return ((array_key_exists('js', $tmp)) ? $tmp['js'] : false);
        }
        return false;
    }

    public function setJsResource($jsResource)
    {
        $properties = $this->getProperties();
        if (!isset($properties['resources'])) $properties['resources'] = [];
        if (!isset($properties['resources']['js'])) $properties['resources']['js'] = [];
        $properties['resources']['js'] = [];

        switch(true){
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
        $aclReference = (string) $aclReference;
        $properties = $this->getProperties();
        $properties['aclReference'] = $aclReference;
        $this->setProperties($properties);
        return $this;
    }

    public function getAclRefence()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('aclReference', $properties)) ? $properties['aclReference'] : false);
    }

    public function setObject($object)
    {
        $object = (string) $object;
        $properties = $this->getProperties();
        $properties['object'] = $object;
        $this->setProperties($properties);
        return $this;
    }

    public function getObject()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('object', $properties)) ? $properties['object'] : false);
    }

    public function setTypeObj($typeObj)
    {
        $typeObj = (string) $typeObj;
        $properties = $this->getProperties();
        $properties['typeObj'] = $typeObj;
        $this->setProperties($properties);
        return $this;
    }

    public function getTypeObj()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('typeObj', $properties)) ? $properties['typeObj'] : false);
    }

    public function setClassName($className)
    {
        $className = (string) $className;
        $properties = $this->getProperties();
        $properties['className'] = $className;
        $this->setProperties($properties);
        return $this;
    }

    public function getClassName()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('className', $properties)) ? $properties['className'] : false);
    }

    public function enable()
    {
        $properties          = $this->getProperties();
        $properties['state'] = self::STATE_ENABLE;
        $this->setProperties($properties);
        return $this;
    }

    public function disable()
    {
        $properties          = $this->getProperties();
        $properties['state'] = self::STATE_DISABLE;
        $this->setProperties($properties);
        return $this;
    }

    public function getState()
    {
        $properties          = $this->getProperties();
        return (($properties['state'] === true) ? 'enable' : 'disable');
    }

    public function setErreur($libel,  $backgroundColor = "FFEEEE", $color = "FF0000")
    {
        $libel = (string) $libel;
        if (!empty($libel)) {
            $properties = $this->getProperties();
            if (!array_key_exists('erreur', $properties)) $properties['erreur'] = [];
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
        $properties          = $this->getProperties();
        return (array_key_exists('erreur', $properties) ? $properties['erreur'] : false);
    }

    public function enaAutoCenter(string $width, string $height)
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

    /*
     * méthode interne à la classe OObject
     */

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
        if (empty($this->const_display)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($constant, 'DISPLAY_');
                if ($pos === false) unset($constants[$key]);
            }
            $this->const_display = $constants;
        } else {
            $constants = $this->const_display;
        }
        return $constants;
    }

    protected function getInfoBullesConst()
    {
        if (empty($this->const_infoBulle)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'INFOBULLE');
                if ($pos === false) unset($constants[$key]);
            }
            $this->const_infoBulle = $constants;
        } else {
            $constants = $this->const_infoBulle;
        }
        return $constants;
    }

    protected function getTypesConst()
    {
        if (empty($this->const_type)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'TYPE');
                if ($pos === false) unset($constants[$key]);
            }
            $this->const_type = $constants;
        } else {
            $constants = $this->const_type;
        }
        return $constants;
    }

    protected function getTriggersConst()
    {
        if (empty($this->const_trigger)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'TRIGGER');
                if ($pos === false) unset($constants[$key]);
            }
            $this->const_trigger = $constants;
        } else {
            $constants = $this->const_trigger;
        }
        return $constants;
    }

    protected function getStatesConst()
    {
        if (empty($this->const_state)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'TRIGGER');
                if ($pos === false) unset($constants[$key]);
            }
            $this->const_state = $constants;
        } else {
            $constants = $this->const_state;
        }
        return $constants;
    }

}
