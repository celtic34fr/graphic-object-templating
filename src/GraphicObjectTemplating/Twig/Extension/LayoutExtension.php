<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 07/02/16
 * Time: 15:01
 */

namespace GraphicObjectTemplating\Twig\Extension;

use Exception;

class LayoutExtension extends \Twig_Extension
{

    public function getFunctions() {
        return array(
            'getclass'       => new \Twig_Function_Method($this, 'getClass'),
            'gettype'        => new \Twig_Function_Method($this, 'getType'),
            'arrayexception' => new \Twig_Function_Method($this, 'arrayException'),
            'checkBoolean'   => new \Twig_Function_Method($this, 'isBoolean'), // KALANTwigExtension @author LAURE
            'substr'         => new \Twig_Function_Method($this, 'subString'),
            'strpos'         => new \Twig_Function_Method($this, 'strPos'),
            'instring'       => new \Twig_Function_Method($this, 'inString'),
        );
    }

    public function getTests()
    {
        return array(
            'typeOf'        => new \Twig_Function_Method($this, 'typeOf'),
            'objInstanceOf' => new \Twig_Function_Method($this, 'ObjectInstanceOf'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'layout_extension';
    }


    public function ObjectInstanceOf($object, $class)
    {
        $reflectionClass = new \ReflectionClass($class);
        return $reflectionClass->isInstance($object);
    }

    public function inString($var1, $var2)
    { // is var1 in var2
        $var1 = (string) $var1;
        $var2 = (string) $var2;
        return (strpos($var2, $var1) !== false) ? true : false;
    }

    public function getType($object = NULL)
    {
        return gettype($object);
    }

    public function getClass($object = NULL)
    {
        if (gettype($object) == "object") {
            return get_class($object);
        } else {
            return "noClass";
        }
    }

    public function arrayException(Exception $exception)
    {
        $retArray = [];
        $retArray['File']          = $exception->getFile();
        $retArray['Line']          = $exception->getLine();
        $retArray['Message']       = $exception->getMessage();
        $retArray['TraceAsString'] = $exception->getTraceAsString();

        $tmpException              = $exception->getPrevious();
        $arrayPrevious             = [];
        while ($tmpException) {
            $item                  = [];
            $item['Class']         = get_class($tmpException);
            $item['File']          = $tmpException->getFile();
            $item['Line']          = $tmpException->getLine();
            $item['Message']       = $tmpException->getMessage();
            $item['TraceAsString'] = $tmpException->getTraceAsString();

            $arrayPrevious[]       = $item;
            $tmpException          = $tmpException->getPrevious();
        }

        $retArray['Previous']      = $arrayPrevious;

        return $retArray;
    }
    
	/**
	 * Description of KALANTwigExtension
	 * => permet le test sur le type d'un champs dans un template
	 *
	 * @author LAURE
	 */
	public function isBoolean($var) { return is_bool($var); }
	 
    public function typeOf($var, $type_test=null)
    {

        switch ($type_test) {
            default:
                return false;
                break;
            case 'array':
                return is_array($var);
                break;
            case 'bool':
                return is_bool($var);
                break;
            case 'float':
                return is_float($var);
                break;
            case 'int':
                return is_int($var);
                break;
            case 'numeric':
                return is_numeric($var);
                break;
            case 'object':
                return is_object($var);
                break;
            case 'scalar':
                return is_scalar($var);
                break;
            case 'string':
                return is_string($var);
                break;
            case 'datetime':
                return ($var instanceof \DateTime);
                break;
        }
    }

    public function subString($str, $start, $len= null)
    {
        if (intval($len) > 0 ) {
            return substr($str, $start, $len);
        } else {
            return substr($str, $start);
        }
    }

    public function strPos($str, $search, $offset = null)
    {
        if (intval($offset) > 0) {
            return strpos($str, $search, $offset);
        } else {
            return strpos($str, $search);
        }
    }
}
