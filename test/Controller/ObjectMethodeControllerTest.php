<?php


namespace Controller;


use BadFunctionCallException;
use GraphicObjectTemplating\OObjects\OObject;
use http\Exception;
use Laminas\Test\PHPUnit\Controller\AbstractControllerTestCase;

class ObjectMethodeControllerTest extends AbstractControllerTestCase
{
    /*
     * méthodes à tester
     * -----------------
     * __get(string $key)
     * __isset(string $key) : bool
     * __set(string $key, $val)
     * validate_widthBT($val)
     * set_Termplate_ClassName($typeObj, $object, $template): array
     * merge_properties(array $add_properties, array $properties) : array
     * getEvent($key)
     * setEvent(string $key, array $parms): bool
     * issetEvent($key)
     * validate_event(string $key)
     * validate_event_parms(array $parms): array
     * formatEvent(string $class, string $method, bool $stopEvent): array
     * static getConstants(): array
     * getConstantsGroup(string $prefix): array
     * truepath($path)
     * validate_By_Constants($val, string $cle_contants, $default)
     */

    public function testOObjectMethodGet()
    {
        $object = new OObject('test');

        $this->assertTrue($object->__get('id') === $object->id);
        $this->assertTrue($object->id === 'test');
        $this->assertTrue($object->display === OObject::DISPLAY_BLOCK);
        $this->assertNotTrue($object->display === OObject::DISPLAY_NONE);
        $this->assertTrue($object->state);
        $this->assertFalse($object->autoCenter);

    }

    public function testOObjectMethodIsset()
    {
        $object = new OObject('test');

        $this->assertTrue($object->__isset('name'));
        $this->assertTrue((bool) $object->id);
        $this->assertFalse((bool) $object->template);
        $this->assertTrue($object->state);
        /* si variable booléenne et valeur 'false' $o->__isset('var') == !$o->var */
        $this->assertTrue($object->__isset('autoCenter'));

    }

    public function testOObjectMethodSet()
    {
        $object = new OObject('test');

        $object->__set('display', OObject::DISPLAY_NONE);
        $this->assertTrue($object->display === OObject::DISPLAY_NONE);
        $this->assertFalse($object->display === 'coucou');

        $object->__set('display', 'coucou');
        $this->assertFalse($object->display === 'coucou');
        $this->assertTrue($object->display === OObject::DISPLAY_BLOCK);

        $object->display = 'coucou';
        $this->assertFalse($object->display === 'coucou');
        $this->assertTrue($object->display === OObject::DISPLAY_BLOCK);

        $object->state = false;
        $this->assertTrue($object->state === false);
        $this->assertFalse($object->state === true);

        $object->autoCenter = 'Bonjour';
        $this->assertFalse($object->autoCenter === 'Bonjour');
        $this->assertTrue($object->autoCenter);
        $object->autoCenter = '';
        $this->assertFalse($object->autoCenter === 'Bonjour');
        $this->assertNotTrue($object->autoCenter);
        $object->autoCenter = 5;
        $this->assertFalse($object->autoCenter === 5);
        $this->assertTrue($object->autoCenter);
        $object->autoCenter = 0;
        $this->assertFalse($object->autoCenter === 5);
        $this->assertNotTrue($object->autoCenter);

        $object->template = 'unTemplate';
        $this->assertTrue($object->template === 'unTemplate');
        $this->assertFalse(!isset($object->template));

        $object->widthBT = 12;
        $this->assertTrue($object->widthBT === "WL12:WM12:WS12:WX12");
        $object->widthBT = "W4:O2";
        $this->assertTrue($object->widthBT === "WL04:WM04:WS04:WX04:OL02:OM02:OS02:OX02");
        $object->widthBT = "WM4:OM1:WX8:OX2";
        $this->assertTrue($object->widthBT === "WM04:WX08:OM01:OX02");

        try {
            $object->events = "events";
        } catch (BadFunctionCallException $e) {
            $this->assertTrue(empty($object->events));
        } catch (\Exception $e) {
            throw new \Exception("une erreur est survenue : ".$e->getMessage());
        }
//        var_dump($object->widthBT);

        // TODO : contrôle affectation à infoBulle

    }
}