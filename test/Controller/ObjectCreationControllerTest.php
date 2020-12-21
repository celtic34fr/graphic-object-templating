<?php


namespace Controller;


use GraphicObjectTemplating\OObjects\ODContained;
use GraphicObjectTemplating\OObjects\ODContained\ODButton;
use GraphicObjectTemplating\OObjects\ODContained\ODCheckbox;
use GraphicObjectTemplating\OObjects\ODContained\ODInput;
use GraphicObjectTemplating\OObjects\ODContained\ODNotification;
use GraphicObjectTemplating\OObjects\ODContained\ODRadio;
use GraphicObjectTemplating\OObjects\ODContained\ODTable;
use GraphicObjectTemplating\OObjects\OObject;
use GraphicObjectTemplating\OObjects\OSContainer;
use GraphicObjectTemplating\OObjects\OSContainer\OSDiv;
use GraphicObjectTemplating\OObjects\OSContainer\OSForm;
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

        $this->OObjectValidationOnly($object);
    }

    /** ODContained and relatives objects */
    public function testODContainedCreation()
    {
        $object = new ODContained('test');

        $this->OObjectValidationOnly($object);
        $this->ODContainedValidation($object);
    }

    public function testODButtonCreation()
    {
        $object = new ODButton('test');

        $this->OObjectValidationFinal($object, 'odbutton', 'odcontained');
        $this->ODContainedValidation($object);

        $this->assertArrayHasKey('type', $object->properties);
        $this->assertTrue($object->type === ODButton::BUTTONTYPE_CUSTOM);

        $this->assertArrayHasKey('label', $object->properties);
        $this->assertTrue($object->label === null);

        $this->assertArrayHasKey('icon', $object->properties);
        $this->assertTrue($object->icon === null);

        $this->assertArrayHasKey('pathFile', $object->properties);
        $this->assertTrue($object->pathFile === null);

        $this->assertArrayHasKey('nature', $object->properties);
        $this->assertTrue($object->nature === ODButton::BUTTONNATURE_DEFAULT);

        $this->assertArrayHasKey('custom', $object->properties);
        $this->assertTrue($object->custom === null);

        $this->assertArrayHasKey('customBorder', $object->properties);
        $this->assertTrue($object->customBorder === null);

        $this->assertArrayHasKey('customColor', $object->properties);
        $this->assertTrue($object->customColor === null);

        $this->assertArrayHasKey('left', $object->properties);
        $this->assertTrue($object->left === null);

        $this->assertArrayHasKey('top', $object->properties);
        $this->assertTrue($object->top === null);

        $this->assertArrayHasKey('width', $object->properties);
        $this->assertFalse($object->width !== null);

        $this->assertArrayHasKey('height', $object->properties);
        $this->assertFalse($object->height !== null);
    }

    public function testODCheckboxCreation()
    {
        $object = new ODCheckbox('test');

        $this->OObjectValidationFinal($object, 'odcheckbox', 'odcontained');
        $this->ODContainedValidation($object);

        $this->assertArrayHasKey('label', $object->properties);
        $this->assertTrue($object->label === null);

        $this->assertArrayHasKey('options', $object->properties);
        $this->assertTrue(is_array($object->options));
        $this->assertTrue(empty($object->options));

        $this->assertArrayHasKey('forme', $object->properties);
        $this->assertNotTrue($object->forme === null);
        $this->assertTrue($object->forme === ODCheckbox::CHECKFORME_HORIZONTAL);

        $this->assertArrayHasKey('hMargin', $object->properties);
        $this->assertNotTrue($object->hMargin === null);
        $this->assertTrue(is_string($object->hMargin));
        $this->assertTrue($object->hMargin === "0");

        $this->assertArrayHasKey('vMargin', $object->properties);
        $this->assertNotTrue($object->vMargin === null);
        $this->assertTrue(is_string($object->vMargin));
        $this->assertTrue($object->vMargin === "0");

        $this->assertArrayHasKey('eventChk', $object->properties);
        $this->assertTrue(is_array($object->eventChk));
        $this->assertTrue(empty($object->eventChk));

        $this->validate_label_input_BT($object);

        $this->assertArrayHasKey('placement', $object->properties);
        $this->assertNotTrue($object->placement === null);
        $this->assertTrue($object->placement === ODCheckbox::CHECKPLACEMENT_LEFT);

        $this->assertArrayHasKey('width', $object->properties);
        $this->assertFalse($object->width !== null);

        $this->assertArrayHasKey('height', $object->properties);
        $this->assertFalse($object->height !== null);

        /** test existance méthodes */
        $methods = [
            'enaDispBySide', 'enaDispUnder', 'enaCheckBySide', 'enaCheckUnder', 'addOption', 'rmOption', 'setOption',
            'getOption', 'checkOption', 'uncheckOption', 'checkAllOptions', 'uncheckAllOptions', 'getCheckedOptions',
            'enaOption', 'disOption', 'getStateOption', 'enaAllOptions', 'disAllOptions', 'getStateOptions'
        ];
        $this->validate_methods($object, $methods);
    }

    public function testODInputCreation()
    {
        $object = new ODInput('test');

        $this->OObjectValidationFinal($object, 'odinput', 'odcontained');
        $this->ODContainedValidation($object);

        $this->assertArrayHasKey('type', $object->properties);
        $this->assertTrue($object->type === ODInput::INPUTTYPE_TEXT);

        $this->assertArrayHasKey('size', $object->properties);
        $this->assertTrue($object->size === null);

        $this->assertArrayHasKey('minLength', $object->properties);
        $this->assertTrue($object->minLength === null);

        $this->assertArrayHasKey('maxLength', $object->properties);
        $this->assertTrue($object->maxLength === null);

        $this->assertArrayHasKey('label', $object->properties);
        $this->assertTrue($object->label === null);

        $this->assertArrayHasKey('placeholder', $object->properties);
        $this->assertTrue($object->placeholder === null);

        $this->assertArrayHasKey('labelWidthBT', $object->properties);
        $this->assertTrue($object->labelWidthBT === null);

        $this->assertArrayHasKey('inputWidthBT', $object->properties);
        $this->assertTrue($object->inputWidthBT === null);

        $this->assertArrayHasKey('mask', $object->properties);
        $this->assertTrue($object->mask === null);

        $this->assertArrayHasKey('valMin', $object->properties);
        $this->assertTrue($object->valMin === null);

        $this->assertArrayHasKey('valMax', $object->properties);
        $this->assertTrue($object->valMax === null);

        $this->assertArrayHasKey('reveal_pwd', $object->properties);
        $this->assertTrue($object->reveal_pwd !== null);
        $this->assertFalse($object->autoFocus);

        $this->assertArrayHasKey('width', $object->properties);
        $this->assertFalse($object->width !== null);

        $this->assertArrayHasKey('height', $object->properties);
        $this->assertFalse($object->height !== null);
    }

    public function testODNotificationCreation()
    {
        $object = new ODNotification('test');

        $this->OObjectValidationFinal($object, 'odnotification', 'odcontained');
        $this->ODContainedValidation($object);

        $this->assertArrayHasKey('type', $object->properties);
        $this->assertTrue($object->type === ODNotification::NOTIFICATIONTYPE_INFO);

        $this->assertArrayHasKey('title', $object->properties);
        $this->assertFalse($object->title !== null);

        $this->assertArrayHasKey('body', $object->properties);
        $this->assertFalse($object->body !== null);

        $this->assertArrayHasKey('size', $object->properties);
        $this->assertNotTrue($object->size === null);
        $this->assertTrue($object->size === ODNotification::NOTIFICATIONSIZE_NORMAL);

        $this->assertArrayHasKey('action', $object->properties);
        $this->assertNotTrue($object->action === null);
        $this->assertTrue($object->action === ODNotification::NOTIFICATIONACTION_INIT);

        $this->assertArrayHasKey('sound', $object->properties);
        $this->assertTrue($object->sound !== null);
        $this->assertTrue($object->sound == ODNotification::BOOLEAN_TRUE);

        $this->assertArrayHasKey('soundExt', $object->properties);
        $this->assertTrue($object->soundExt !== null);
        $this->assertTrue($object->soundExt == ".ogg");

        $this->assertArrayHasKey('soundPath', $object->properties);
        $this->assertTrue($object->soundPath !== null);
        $this->assertTrue($object->soundPath == 'graphicobjecttemplating/objects/odcontained/odnotification/sounds/');

        $this->assertArrayHasKey('delay', $object->properties);
        $this->assertTrue($object->delay !== null);
        $this->assertTrue($object->delay == 3000);

        $this->assertArrayHasKey('delayIndicator', $object->properties);
        $this->assertTrue($object->delayIndicator !== null);
        $this->assertTrue($object->delayIndicator == ODNotification::BOOLEAN_TRUE);

        $this->assertArrayHasKey('position', $object->properties);
        $this->assertTrue($object->position === ODNotification::NOTIFICATIONPOSITION_BR);

        $this->assertArrayHasKey('showAfterPrevious', $object->properties);
        $this->assertTrue($object->showAfterPrevious !== null);
        $this->assertTrue($object->showAfterPrevious == ODNotification::BOOLEAN_FALSE);

        $this->assertArrayHasKey('delayMessage', $object->properties);
        $this->assertTrue($object->delayMessage !== null);
        $this->assertTrue($object->delayMessage == 2000);

        $this->assertArrayHasKey('showClass', $object->properties);
        $this->assertTrue($object->showClass === ODNotification::NOTIFICATIONSHOW_ZOOMIN);

        $this->assertArrayHasKey('hideClass', $object->properties);
        $this->assertTrue($object->hideClass === ODNotification::NOTIFICATIONHIDE_ZOOMOUT);

        $this->assertArrayHasKey('icon', $object->properties);
        $this->assertTrue($object->icon !== null);
        $this->assertTrue($object->icon == ODNotification::BOOLEAN_TRUE);

        $this->assertArrayHasKey('iconSource', $object->properties);
        $this->assertTrue($object->iconSource === ODNotification::NOTIFICATIONICON_BOOTSTRAP);

        $this->assertArrayHasKey('width', $object->properties);
        $this->assertTrue($object->width !== null);
        $this->assertTrue($object->width == 600);

        $this->assertArrayHasKey('height', $object->properties);
        $this->assertTrue($object->height !== null);
        $this->assertTrue($object->height == 'auto');

        $this->assertArrayHasKey('closable', $object->properties);
        $this->assertTrue($object->closable !== null);
        $this->assertTrue($object->closable == ODNotification::BOOLEAN_TRUE);

        $this->assertArrayHasKey('closeOnClick', $object->properties);
        $this->assertTrue($object->closeOnClick !== null);
        $this->assertTrue($object->closeOnClick == ODNotification::BOOLEAN_TRUE);

        /** test existance méthodes */
        $methods = [
            'enaDinstinctMessage', 'disDinstinctMessage',
        ];
        $this->validate_methods($object, $methods);
    }

    public function testODRadioCreation()
    {
        $object = new ODRadio('test');

        $this->OObjectValidationFinal($object, 'odradio', 'odcontained');
        $this->ODContainedValidation($object);

        $this->assertArrayHasKey('label', $object->properties);
        $this->assertTrue($object->label === null);

        $this->assertArrayHasKey('options', $object->properties);
        $this->assertTrue(is_array($object->options));
        $this->assertTrue(empty($object->options));

        $this->assertArrayHasKey('forme', $object->properties);
        $this->assertNotTrue($object->forme === null);
        $this->assertTrue($object->forme === ODRadio::RADIOFORM_HORIZONTAL);

        $this->assertArrayHasKey('hMargin', $object->properties);
        $this->assertNotTrue($object->hMargin === null);
        $this->assertTrue(is_string($object->hMargin));
        $this->assertTrue($object->hMargin === "0");

        $this->assertArrayHasKey('vMargin', $object->properties);
        $this->assertNotTrue($object->vMargin === null);
        $this->assertTrue(is_string($object->vMargin));
        $this->assertTrue($object->vMargin === "0");

        $this->assertArrayHasKey('place', $object->properties);
        $this->assertNotTrue($object->place === null);
        $this->assertTrue($object->place === ODRadio::RADIOPLACEMENT_LEFT);

        $this->validate_label_input_BT($object);

        $this->assertArrayHasKey('placement', $object->properties);
        $this->assertNotTrue($object->placement === null);
        $this->assertTrue($object->placement === ODRadio::RADIOPLACEMENT_LEFT);

        /** test existance méthodes */
        $methods = [
            'enaDispBySide', 'enaDispUnder', 'addOption', 'rmOption', 'setOption',
            'getOption', 'checkOption', 'uncheckOption', 'enaOption', 'disOption',
        ];
        $this->validate_methods($object, $methods);
    }

    public function testODTableCreation()
    {
        $object = new ODTable('test');

//        var_dump($object);
//        var_dump(get_class_methods($object));

        $this->OObjectValidationFinal($object, 'odtable', 'odcontained');
    }

//    public function testODSelectCreation()
//    {
//        $object = new ODSelect('test');

//        var_dump($object);
//        var_dump(get_class_methods($object));

//        $this->OObjectValidationFinal($object, 'odselect', 'odcontained');
//    }

    /** OSContainer and relatives objects */
    public function testOSContainerCreation()
    {
        $object = new OSContainer('test');

        $this->OObjectValidationOnly($object);
        $this->OSContainerValidation($object);
    }

    public function testOSDivCreation()
    {
        $object = new OSDiv('test');

        $this->OObjectValidationFinal($object, 'osdiv', 'oscontainer');
        $this->OSContainerValidation($object);
    }

    public function testOSFormCreation()
    {
        $object = new OSForm('test');

//        var_dump($object);
//        var_dump(get_class_methods($object));

        $this->OObjectValidationFinal($object, 'osform', 'oscontainer');
        $this->OSContainerValidation($object);

        $this->assertArrayHasKey('title', $object->properties);
        $this->assertTrue($object->title === null);

        $this->assertArrayHasKey('origine', $object->properties);
        $this->assertTrue(is_array($object->origine));
        $this->assertTrue(empty($object->origine));

        $this->assertArrayHasKey('hidden', $object->properties);
        $this->assertTrue(is_array($object->hidden));
        $this->assertTrue(empty($object->hidden));

        $this->assertArrayHasKey('required', $object->properties);
        $this->assertTrue(is_array($object->required));
        $this->assertTrue(empty($object->required));

        $this->assertArrayHasKey('submitEnter', $object->properties);
        $this->assertTrue($object->submitEnter !== null);
        $this->assertFalse($object->submitEnter);

        $this->assertArrayHasKey('btnsControls', $object->properties);
        $this->assertTrue($object->btnsControls !== null);
        $this->assertTrue(get_class($object->btnsControls) === OSDiv::class);
        $this->assertTrue($object->btnsControls->id == $object->id."Ctrls");

        $this->assertArrayHasKey('btnsDisplay', $object->properties);
        $this->assertTrue($object->btnsDisplay === OSForm::DISP_BTN_HORIZONTAL);

        $this->assertArrayHasKey('btnsWidthBT', $object->properties);
        $this->assertTrue($object->btnsWidthBT === "2:2:2:2");

        $this->assertArrayHasKey('widthBTbody', $object->properties);
        $this->assertTrue($object->widthBTbody === "12:12:12:12");

        $this->assertArrayHasKey('widthBTctrls', $object->properties);
        $this->assertTrue($object->widthBTctrls === "12:12:12:12");

        /** test existance méthodes */
        $methods = [
            'alter_btnsControls', 'addBtnCtrl',
        ];
        $this->validate_methods($object, $methods);
    }


    public function OObjectValidationBase($object)
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

        $this->assertArrayHasKey('display', $object->properties);
        $this->assertTrue($object->display === OObject::DISPLAY_BLOCK);

        $this->assertArrayHasKey('lastAccess', $object->properties);
        $this->assertFalse($object->lastAccess !== null);

        $this->assertArrayHasKey('state', $object->properties);
        $this->assertTrue($object->state !== null);
        $this->assertTrue($object->state);

        $this->assertArrayHasKey('classes', $object->properties);
        $this->assertFalse($object->classes !== null);

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

    private function OObjectValidationOnly($object)
    {
        $this->OObjectValidationBase($object);

        $this->assertArrayHasKey('className', $object->properties);
        $this->assertTrue(empty($object->className));
        $this->assertNotTrue(get_class($object) === $object->className);

        $this->assertArrayHasKey('object', $object->properties);
        $this->assertFalse($object->object !== null);

        $this->assertArrayHasKey('typeObj', $object->properties);
        $this->assertFalse($object->typeObj !== null);

        $this->assertArrayHasKey('template', $object->properties);
        $this->assertFalse($object->template !== null);

        $this->assertArrayHasKey('widthBT', $object->properties);
        $this->assertFalse($object->widthBT !== null);

        $this->assertArrayHasKey('width', $object->properties);
        $this->assertFalse($object->width !== null);

        $this->assertArrayHasKey('height', $object->properties);
        $this->assertFalse($object->height !== null);
    }

    private function OObjectValidationFinal($object, $objectStr, $typObjStr)
    {
        $this->OObjectValidationBase($object);

        $this->assertArrayHasKey('className', $object->properties);
        $this->assertNotTrue(empty($object->className));
        $this->assertTrue(get_class($object) === $object->className);

        $this->assertArrayHasKey('object', $object->properties);
        $this->assertNotFalse($object->object !== null);
        $this->assertTrue($object->object === $objectStr);

        $this->assertArrayHasKey('typeObj', $object->properties);
        $this->assertNotFalse($object->typeObj !== null);
        $this->assertTrue($object->typeObj === $typObjStr);

        $this->assertArrayHasKey('template', $object->properties);
        $this->assertNotFalse($object->template !== null);
        $this->assertTrue($object->template == "graphicobjecttemplating/oobjects/$typObjStr/$objectStr/$objectStr");

        $this->assertArrayHasKey('widthBT', $object->properties);
        $this->assertNotFalse($object->widthBT !== null);
        $this->assertTrue($object->widthBT === "12:12:12:12");
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
        $this->assertTrue(is_array($object->event));
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

    private function validate_label_input_BT($object)
    {
        $this->assertArrayHasKey('labelWidthBT', $object->properties);
        $this->assertTrue($object->labelWidthBT === null);

        $this->assertArrayHasKey('inputWidthBT', $object->properties);
        $this->assertTrue($object->inputWidthBT === null);

        $this->assertArrayHasKey('checkLabelWidthBT', $object->properties);
        $this->assertTrue($object->checkLabelWidthBT === null);

        $this->assertArrayHasKey('checkInputWidthBT', $object->properties);
        $this->assertTrue($object->checkInputWidthBT === null);
    }
}