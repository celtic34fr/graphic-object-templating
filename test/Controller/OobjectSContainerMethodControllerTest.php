<?php


namespace Controller;


use GraphicObjectTemplating\OObjects\OSContainer;
use Laminas\Test\PHPUnit\Controller\AbstractControllerTestCase;

class OobjectSContainerMethodControllerTest extends AbstractControllerTestCase
{
    /*
     * méthodes à tester
     * -----------------
     * __isset(string $key) : bool
     * __get(string $key)
     * __set(string $key, $val)
     *
     * addChild(OObject $child, string $mode = self::MODE_LAST, $params = null)
     * addChildABN($child, $mode, $params)
     * rmChild($child)
     * isChild($child) : bool
     * r_isChild(string $searchChild, OObject $child, string $path = '') : string
     * r_isset(string $key, OObject $child) : bool
     * existChild($child)
     * hasChild(): bool
     */

    public function testOSContainerIsset()
    {
        $object = new OSContainer('test');

        $this->assertTrue($object->__isset('display'));
        $this->assertFalse($object->__isset('toto'));
    }

    public function testOSContainerGet()
    {
        $object = new OSContainer('test');

        $this->assertTrue($object->__get('display') == 'block');
        $this->assertTrue(empty($object->children));
        $this->assertFalse($object->__get('autoCenter'));

        $this->assertTrue($object->name === "test");
        $this->assertNotTrue($object->id === 'coucou');
    }

    public function testOSContainerSet()
    {
        $object = new OSContainer('test');

        $object->__set('name', 'coucou');
        $this->assertTrue($object->name === "coucou");
        $this->assertNotTrue($object->name === 'test');

        $object->display = OSContainer::DISPLAY_NONE;
        $this->assertTrue($object->display === OSContainer::DISPLAY_NONE);
        $this->assertNotTrue($object->display === OSContainer::DISPLAY_BLOCK);
        $object->display = 'coucou';
        $this->assertTrue($object->display === OSContainer::DISPLAY_BLOCK);
        $this->assertNotTrue($object->display === "coucou");

//        var_dump($object);
    }

}