<?php


namespace Controller;


use GraphicObjectTemplating\OObjects\OObject;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Test\PHPUnit\Controller\AbstractControllerTestCase;

class ObjectControllerTest extends AbstractControllerTestCase
{
    public function setUp() : void
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../config/module.config.php',
            $configOverrides
        ));

        parent::setUp();
    }
    public function testOObjectCreation()
    {
        $object = new OObject('test');

        /** test existance et valeur attribut */
        $this->assertObjectHasAttribute('id', $object);
        $this->assertTrue($object->id === 'test');
        $this->assertFalse($object->id === 'moi');

        $this->assertObjectHasAttribute('name', $object);
        $this->assertTrue($object->name === 'test');
        $this->assertFalse(empty($object-name));

        $this->assertObjectHasAttribute('className', $object);
        $this->assertTrue(empty($object->className));
        $this->assertNotTrue(get_class($object) === $object->className);

        $this->assertObjectHasAttribute('display', $object);
        $this->assertTrue($object->display === OObject::DISPLAY_BLOCK);

        $this->assertObjectHasAttribute('object', $object);
        $this->assertFalse($object->object);

        $this->assertObjectHasAttribute('typeObj', $object);
        $this->assertFalse($object->typeObj);

        $this->assertObjectHasAttribute('template', $object);
        $this->assertFalse($object->template);

        /** test existance méthodes */
        $this->assertTrue(
            method_exists($object, '__construct')
        );
        $this->assertTrue(
            method_exists($object, 'constructor')
        );
        $this->assertTrue(
            method_exists($object, 'object_contructor')
        );
        $this->assertTrue(
            method_exists($object, '__get')
        );
        $this->assertTrue(
            method_exists($object, '__isset')
        );
        $this->assertTrue(
            method_exists($object, '__set')
        );
    }
}