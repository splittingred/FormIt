<?php
/**
 * @package formit-test
 */
/**
 * Tests related to fiDictionary methods
 *
 * @package formit-test
 * @group Core
 * @group fiDictionary
 */
class FiDictionaryClassTest extends FiTestCase {
    /**
     * @var fiDictionary $dictionary
     */
    public $dictionary;

    public function setUp() {
        parent::setUp();
        $_POST = array(
            'name' => 'Mr. Tester',
            'email' => 'bob@email.com',
            'color' => 'blue,red',
        );
        $request = $this->formit->loadRequest();
        $this->dictionary = $request->loadDictionary();
        $this->dictionary->gather();
    }

    public function tearDown() {
        if ($this->dictionary) {
            $this->dictionary->reset();
        }
        parent::tearDown();
    }

    /**
     *
     * @param boolean $shouldPass
     * @param string $key
     * @param mixed $value
     * @param array $request
     * @param array $fields
     * @dataProvider providerGather
     */
    public function testGather($shouldPass,$key,$value,array $request = array(),array $fields = array()) {
        $_POST = $request;
        $this->dictionary->gather($fields);
        if ($shouldPass) {
            $this->assertArrayHasKey($key,$this->dictionary->fields);
            $this->assertEquals($this->dictionary->fields[$key],$value);
        } else {
            $this->assertArrayNotHasKey($key,$this->dictionary->fields);
        }
    }
    /**
     * @return array
     */
    public function providerGather() {
        return array(
            array(true,'one',1,array('one' => 1)),
            array(true,'one',1,array(),array('one' => 1)),
            array(true,'one',1,array('one' => 1),array('one' => 1)),
            array(true,'one',1,array('two' => 2,'one' => 1)),
            array(false,'one',array()),
        );
    }

    /**
     * @param string $key
     * @param mixed $value
     * @dataProvider providerGet
     */
    public function testGet($key,$value) {
        $this->dictionary->fields[$key] = $value;
        $result = $this->dictionary->get($key,$value);
        $this->assertEquals($value,$result);
    }
    /**
     * @return array
     */
    public function providerGet() {
        return array(
            array('one',1),
            array('one',array('two' => 2)),
        );
    }

    /**
     * @param string $key
     * @param mixed $value
     * @dataProvider providerSet
     */
    public function testSet($key,$value) {
        $this->dictionary->set($key,$value);
        $this->assertEquals($value,$this->dictionary->fields[$key]);
    }
    /**
     * @return array
     */
    public function providerSet() {
        return array(
            array('one',1),
            array('one',array('two' => 2)),
        );
    }

    /**
     * @return void
     */
    public function testToArray() {
        $array = $this->dictionary->toArray();
        $this->assertNotEmpty($array);
    }

    /**
     * @param boolean $shouldPass
     * @param array $fields
     * @param string $key
     * @param mixed $value
     * @dataProvider providerFromArray
     */
    public function testFromArray($shouldPass,$fields,$key,$value) {
        $this->dictionary->fromArray($fields);
        if ($shouldPass) {
            $this->assertArrayHasKey($key,$this->dictionary->fields);
            if (isset($this->dictionary->fields[$key])) {
                $this->assertEquals($this->dictionary->fields[$key],$value);
            }
        } else {
            if (isset($this->dictionary->fields[$key])) {
                $this->assertNotEquals($this->dictionary->fields[$key],$value);
            } else {
                $this->assertTrue(true);
            }
        }
    }
    /**
     * @return array
     */
    public function providerFromArray() {
        return array(
            array(true,array('two' => 2),'two',2),
            array(true,array('one' => array('two' => 2)),'one',array('two' => 2)),
            array(false,array('two' => 2),'three',3),
        );
    }

    /**
     * @param string $key
     * @dataProvider providerRemove
     */
    public function testRemove($key) {
        $this->dictionary->fields[$key] = 'Test';
        $this->dictionary->remove($key);
        $this->assertArrayNotHasKey($key,$this->dictionary->fields);
    }
    /**
     * @return array
     */
    public function providerRemove() {
        return array(
            array('one'),
            array(''),
        );
    }

    /**
     * Test the reset method
     * @depends testSet
     */
    public function testReset() {
        $this->dictionary->set('one',1);
        $this->dictionary->reset();
        $this->assertEmpty($this->dictionary->fields);
    }
}