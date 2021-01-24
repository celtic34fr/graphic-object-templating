<?php


namespace Controller;


use BadFunctionCallException;
use GraphicObjectTemplating\OObjects\OObject;
use GraphicObjectTemplating\OObjects\OSTech\OTInfoBulle;
use Laminas\Test\PHPUnit\Controller\AbstractControllerTestCase;
use UnexpectedValueException;

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

        $ib_array = ['setIB' => 'coucou', 'otype' => OTInfoBulle::IBTYPE_TOOLTIP];
        try {
            $object->infoBulle = $ib_array;
        } catch (UnexpectedValueException $e) {
            $this->assertTrue(true);
        } catch (\Exception $e) {
            throw new \Exception("une erreur est survenue : ".$e->getMessage());
        }

        $ib_array = ['setIB' => false, 'type' => OTInfoBulle::IBTYPE_TOOLTIP, 'animation' => true, 'delay_show' => 500,
            'delay_hide' => 100, 'html'=>OObject::BOOLEAN_FALSE, 'placement'=>OTInfoBulle::IBPLACEMENT_TOP, 'title'=>null,
            'content'=>null, 'trigger'=>OTInfoBulle::IBTRIGGER_HOVER];
        try {
            $object->infoBulle = $ib_array;
        } catch (UnexpectedValueException $e) {
            $this->assertTrue(true);
        } catch (\Exception $e) {
            throw new \Exception("une erreur est survenue : ".$e->getMessage());
        }

        $ib_obj = new OTInfoBulle($ib_array);
        $object->infoBulle = $ib_obj;
        $this->assertTrue($object->infoBulle === $ib_obj);

        $ib_obj2 = new OTInfoBulle();
        $this->assertTrue($ib_obj->properties == $ib_obj2->properties);
        try {
            $ib_obj3 = new OTInfoBulle(['test'=>'test']);
        } catch (UnexpectedValueException $e) {
            $this->assertTrue(true);
        } catch (\Exception $e) {
            throw new \Exception("une erreur est survenue : ".$e->getMessage());
        }

        $this->assertTrue($ib_obj->setIB === false);
        $ib_obj->setIB = true;
        $this->assertFalse($ib_obj->setIB === false);
        $ib_obj->setIB = 0;
        $this->assertTrue($ib_obj->setIB === false);
        $ib_obj->setIB = 40;
        $this->assertFalse($ib_obj->setIB === false);
        $ib_obj->setIB = '';
        $this->assertTrue($ib_obj->setIB === false);
        $ib_obj->setIB = 'test';
        $this->assertFalse($ib_obj->setIB === false);

        $this->assertTrue($ib_obj->type === OTInfoBulle::IBTYPE_TOOLTIP);
        $ib_obj->type = OTInfoBulle::IBTYPE_POPOVER;
        $this->assertTrue($ib_obj->type === OTInfoBulle::IBTYPE_POPOVER);
        $ib_obj->type = 'type';
        $this->assertTrue($ib_obj->type === OTInfoBulle::IBTYPE_TOOLTIP);
        $this->assertNotTrue($ib_obj->type === 'type');

        $this->assertTrue($ib_obj->animation === true);
        $ib_obj->animation = false;
        $this->assertFalse($ib_obj->animation === true);
        $ib_obj->animation = 0;
        $this->assertTrue($ib_obj->animation === false);
        $ib_obj->animation = 40;
        $this->assertFalse($ib_obj->animation === false);
        $ib_obj->animation = '';
        $this->assertTrue($ib_obj->animation === false);
        $ib_obj->animation = 'test';
        $this->assertFalse($ib_obj->animation === false);

        $this->assertTrue($ib_obj->delay_show === 500);
        $ib_obj->delay_show = 0;
        $this->assertTrue($ib_obj->delay_show === 500);
        $this->assertFalse($ib_obj->delay_show === 0);
        $ib_obj->delay_show = 250;
        $this->assertTrue($ib_obj->delay_show === 250);
        $this->assertFalse($ib_obj->delay_show === 500);
        $ib_obj->delay_show = '';
        $this->assertTrue($ib_obj->delay_show === 500);
        $this->assertFalse($ib_obj->delay_show === 0);
        $ib_obj->delay_show = '250';
        $this->assertTrue($ib_obj->delay_show === 250);
        $this->assertFalse($ib_obj->delay_show === 500);
        $ib_obj->delay_show = 'd250s';
        $this->assertTrue($ib_obj->delay_show === 500);
        $this->assertFalse($ib_obj->delay_show === 0);

        $this->assertTrue($ib_obj->delay_hide === 100);
        $ib_obj->delay_hide = 0;
        $this->assertTrue($ib_obj->delay_hide === 100);
        $this->assertFalse($ib_obj->delay_hide === 0);
        $ib_obj->delay_hide = 250;
        $this->assertTrue($ib_obj->delay_hide === 250);
        $this->assertFalse($ib_obj->delay_hide === 100);
        $ib_obj->delay_hide = '';
        $this->assertTrue($ib_obj->delay_hide === 100);
        $this->assertFalse($ib_obj->delay_hide === 0);
        $ib_obj->delay_hide = '250';
        $this->assertTrue($ib_obj->delay_hide === 250);
        $this->assertFalse($ib_obj->delay_hide === 100);
        $ib_obj->delay_hide = 'd100s';
        $this->assertTrue($ib_obj->delay_hide === 100);
        $this->assertFalse($ib_obj->delay_hide === 0);

        $this->assertTrue($ib_obj->html === OObject::BOOLEAN_FALSE);
        $ib_obj->html = OObject::BOOLEAN_TRUE;
        $this->assertNotTrue($ib_obj->html === OObject::BOOLEAN_FALSE);
        $this->assertTrue($ib_obj->html === OObject::BOOLEAN_TRUE);
        $ib_obj->html = '';
        $this->assertTrue($ib_obj->html === OObject::BOOLEAN_FALSE);
        $this->assertNotTrue($ib_obj->html === OObject::BOOLEAN_TRUE);
        $ib_obj->html = 'True';
        $this->assertNotTrue($ib_obj->html === OObject::BOOLEAN_FALSE);
        $this->assertTrue($ib_obj->html === OObject::BOOLEAN_TRUE);
        $ib_obj->html = 0;
        $this->assertTrue($ib_obj->html === OObject::BOOLEAN_FALSE);
        $this->assertNotTrue($ib_obj->html === OObject::BOOLEAN_TRUE);
        $ib_obj->html = 1;
        $this->assertNotTrue($ib_obj->html === OObject::BOOLEAN_FALSE);
        $this->assertTrue($ib_obj->html === OObject::BOOLEAN_TRUE);

        $this->assertTrue($ib_obj->placement === OTInfoBulle::IBPLACEMENT_TOP);
        $ib_obj->placement = OTInfoBulle::IBPLACEMENT_AUTO;
        $this->assertTrue($ib_obj->placement === OTInfoBulle::IBPLACEMENT_AUTO);
        $ib_obj->placement = 'placement';
        $this->assertTrue($ib_obj->placement === OTInfoBulle::IBPLACEMENT_TOP);
        $this->assertNotTrue($ib_obj->placement === 'type');

        $this->assertNotTrue($ib_obj->title);
        $ib_obj->title = 'un titre';
        $this->assertTrue($ib_obj->title === 'un titre');
        $this->assertTrue(!empty($ib_obj->title));
        $ib_obj->title = 0;
        $this->assertTrue($ib_obj->title === '0');
        $this->assertTrue(empty($ib_obj->title));
        $this->assertTrue(strlen($ib_obj->title) === 1);
        $ib_obj->title = 500;
        $this->assertTrue($ib_obj->title === '500');
        $this->assertNotTrue(empty($ib_obj->title));
        $this->assertTrue(strlen($ib_obj->title) === 3);

        $this->assertNotTrue($ib_obj->content);
        $ib_obj->content = 'un contenu';
        $this->assertTrue($ib_obj->content === 'un contenu');
        $this->assertTrue(!empty($ib_obj->content));
        $ib_obj->content = 0;
        $this->assertTrue($ib_obj->content === '0');
        $this->assertTrue(empty($ib_obj->content));
        $this->assertTrue(strlen($ib_obj->content) === 1);
        $ib_obj->content = 500;
        $this->assertTrue($ib_obj->content === '500');
        $this->assertNotTrue(empty($ib_obj->content));
        $this->assertTrue(strlen($ib_obj->content) === 3);

        $this->assertTrue($ib_obj->trigger === OTInfoBulle::IBTRIGGER_HOVER);
        $ib_obj->trigger = OTInfoBulle::IBTRIGGER_FOCUS;
        $this->assertTrue($ib_obj->trigger === OTInfoBulle::IBTRIGGER_FOCUS);
        $ib_obj->trigger = 'trigger';
        $this->assertTrue($ib_obj->trigger === OTInfoBulle::IBTRIGGER_HOVER);
        $this->assertNotTrue($ib_obj->trigger === 'trigger');

        $this->assertTrue($ib_obj->__isset('setIB'));
        $this->assertNotTrue($ib_obj->__isset('setRR'));
        $this->assertTrue($ib_obj->setIB);
        $this->assertNotTrue($ib_obj->setRR);
    }
}