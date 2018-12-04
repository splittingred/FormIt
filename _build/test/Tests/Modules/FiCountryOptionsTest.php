<?php
/**
 * @package formit-test
 */
/**
 * Tests related to the FiCountryOptions snippet
 *
 * @package formit-test
 * @group Modules
 * @group FiCountryOptions
 */
class FiCountryOptionsTest extends FiTestCase {
    /**
     * @var fiCountryOptions $module
     */
    public $module;

    public function setUp() {
        parent::setUp();
        $this->module = $this->formit->loadModule('fiCountryOptions','countryOptions');
    }

    public function tearDown() {
        $this->formit->countryOptions = null;
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
        $this->assertArrayHasKey('US',$data);
    }

    /**
     * @param boolean $shouldPass
     * @param string $prioritized
     * @dataProvider providerLoadPrioritized
     */
    public function testLoadPrioritized($shouldPass,$prioritized) {
        $this->module->initialize();
        $this->module->setOption('prioritized',$prioritized);
        $this->module->getData();
        $prioritized = $this->module->loadPrioritized();
        if ($shouldPass) {
            $this->assertNotEmpty($prioritized);
        } else {
            $this->assertEmpty($prioritized);
        }
    }
    /**
     * @return array
     */
    public function providerLoadPrioritized() {
        return array(
            array(true,'US,DE'),
            array(true,'US,GB,DE'),
            array(false,''),
        );
    }
}