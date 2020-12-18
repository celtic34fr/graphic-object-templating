<?php


namespace Controller;


use GraphicObjectTemplating\OObjects\ODContained;
use GraphicObjectTemplating\OObjects\OObject;
use GraphicObjectTemplating\OObjects\OSContainer;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Test\PHPUnit\Controller\AbstractControllerTestCase;

class ObjectCreationControllerTest extends AbstractControllerTestCase
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

        $this->OObjectValidation($object);
    }

    public function testODContainedCreation()
    {
        $object = new ODContained('test');

        $this->OObjectValidation($object);
        $this->ODContainedValidation($object);
    }

    public function testOSContainerCreation()
    {
        $object = new OSContainer('test');

        $this->OObjectValidation($object);
        $this->OSContainerValidation($object);
    }


    private function OObjectValidation($object)
    {
        /** test existance et valeur attribut */
        $this->assertObjectHasAttribute('id', $object);
        $this->assertObjectHasAttribute('properties', $object);
        $this->assertTrue($object->id === 'test');
        $this->assertFalse($object->id === 'moi');

        $this->assertArrayNotHasKey('test', $object->properties);
        $this->assertTrue($object->test === false);

        $this->assertArrayHasKey('name', $object->properties);
        $this->assertTrue($object->name === 'test');
        $this->assertNotTrue($object->name === 'moi');
        $this->assertFalse(empty($object->name));

        $this->assertArrayHasKey('className', $object->properties);
        $this->assertTrue(empty($object->className));
        $this->assertNotTrue(get_class($object) === $object->className);

        $this->assertArrayHasKey('display', $object->properties);
        $this->assertTrue($object->display === OObject::DISPLAY_BLOCK);

        $this->assertArrayHasKey('object', $object->properties);
        $this->assertFalse($object->object !== null);

        $this->assertArrayHasKey('typeObj', $object->properties);
        $this->assertFalse($object->typeObj !== null);

        $this->assertArrayHasKey('template', $object->properties);
        $this->assertFalse($object->template !== null);

        $this->assertArrayHasKey('widthBT', $object->properties);
        $this->assertFalse($object->widthBT !== null);

        $this->assertArrayHasKey('lastAccess', $object->properties);
        $this->assertFalse($object->lastAccess !== null);

        $this->assertArrayHasKey('state', $object->properties);
        $this->assertTrue($object->state !== null);
        $this->assertTrue($object->state);

        $this->assertArrayHasKey('classes', $object->properties);
        $this->assertFalse($object->classes !== null);

        $this->assertArrayHasKey('width', $object->properties);
        $this->assertFalse($object->width !== null);

        $this->assertArrayHasKey('height', $object->properties);
        $this->assertFalse($object->height !== null);

        $this->assertArrayHasKey('autoCenter', $object->properties);
        $this->assertFalse($object->autoCenter === true);
        $this->assertNotTrue($object->autoCenter);

        $this->assertArrayHasKey('acPx', $object->properties);
        $this->assertFalse($object->acPx !== null);

        $this->assertArrayHasKey('acPy', $object->properties);
        $this->assertFalse($object->acPy !== null);

        /** test existance méthodes */
        $methods = [
            '__construct', 'constructor', 'object_contructor', '__get', '__isset', '__set', 'set_Termplate_ClassName',
            'merge_properties', 'getEvent', 'setEvent', 'issetEvent', 'formatEvent', 'getConstants', 'truepath',
            'validate_By_Constants'
        ];
        $this->validate_methods($object, $methods);
    }

    private function ODContainedValidation($object)
    {
        $this->assertArrayHasKey('valeur', $object->properties);
        $this->assertTrue($object->valeur === null);

        $this->assertArrayHasKey('form', $object->properties);
        $this->assertTrue($object->form === null);

        $this->assertArrayHasKey('default', $object->properties);
        $this->assertTrue($object->default === null);

        $this->assertArrayHasKey('event', $object->properties);
        $this->assertTrue(empty($object->event));
    }

    private function OSContainerValidation($object)
    {
        $this->assertArrayHasKey('children', $object->properties);
        $this->assertTrue(empty($object->children));

        $this->assertArrayHasKey('form', $object->properties);
        $this->assertTrue($object->form === null);

        $this->assertArrayHasKey('codeCss', $object->properties);
        $this->assertTrue(empty($object->codeCss));

        /** test existance méthodes */
        $methods = [
            'addChild', 'rmChild', 'isChild', 'r_isChild', 'r_isset', 'existChild', 'hasChild'
        ];
        $this->validate_methods($object, $methods);
    }

    private function validate_methods($object, array $methods)
    {
        foreach ($methods as $method) {
            $this->assertTrue(
                method_exists($object, $method)
            );
        }
    }
}