<?php
/**
 * @package formit-test
 */
/**
 * Tests related to basic FormIt class methods
 *
 * @package formit-test
 * @group Core
 */
class FormItClassTest extends FiTestCase {

    /**
     * Test the FormIt::initialize method and ensure it returns true.
     *
     * @return void
     */
    public function testInitialize() {
        $success = $this->formit->initialize();
        $this->assertTrue($success,'FormIt::initialize() did not return true.');
    }

    /**
     * Test FormIt::loadRequest and ensure it loads a fiRequest instance
     *
     * @todo Test passing a custom request class and path
     * 
     * @return void
     */
    public function testLoadRequest() {
        $request = $this->formit->loadRequest();

        $this->assertInstanceOf('fiRequest',$request,'FormIt::loadRequest did not return an fiRequest type.');
    }
}