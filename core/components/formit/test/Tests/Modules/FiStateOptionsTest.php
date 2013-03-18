<?php
/**
 * @package formit-test
 */
/**
 * Tests related to the FiStateOptions snippet
 *
 * @package formit-test
 * @group Modules
 * @group FiStateOptions
 */
class FiStateOptionsTest extends FiTestCase {
    /**
     * @var fiCountryOptions $module
     */
    public $module;

    public function setUp() {
        parent::setUp();
        $this->module = $this->formit->loadModule('fiStateOptions','stateOptions');
    }

    public function tearDown() {
        $this->formit->stateOptions = null;
        parent::tearDown();
    }

    /**
     * Ensure initialize sets up the default options
     * @return void
     */
    public function testInitialize() {
        $this->module->initialize();
        $this->assertNotEmpty($this->module->config);
        $this->assertArrayHasKey('selectedKey',$this->module->config);
    }

    /**
     * Ensure getData loads the country list
     * @return void
     */
    public function testGetData() {
        $data = $this->module->getData();
        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('TX',$data);
    }
}