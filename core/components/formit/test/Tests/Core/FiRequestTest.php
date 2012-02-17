<?php
/**
 * @package formit-test
 */
/**
 * Tests related to fiRequest methods
 *
 * @package formit-test
 * @group Core
 * @group fiRequest
 */
class FiRequestClassTest extends FiTestCase {
    /** @var fiRequest $request */
    public $request;

    public function setUp() {
        parent::setUp();
        $this->modx->loadClass('fiRequest',$this->formit->config['modelPath'].'formit/',true,true);
        $this->request = new fiRequest($this->formit,$this->formit->config);
    }

    public function tearDown() {
        parent::tearDown();
    }

    /**
     * Ensures hasHook correctly checks for hooks
     * @param string $hook
     * @dataProvider providerHasHook
     */
    public function testHasHook($hook) {
        $this->request->config['hooks'] = $hook;
        $this->assertTrue($this->request->hasHook($hook));
        $this->request->config['hooks'] = 'dummyHook,'.$hook;
        $this->assertTrue($this->request->hasHook($hook));
        $this->request->config['hooks'] = 'dummyHook,'.$hook.',bookendHook';
        $this->assertTrue($this->request->hasHook($hook));
    }
    /**
     * @return array
     */
    public function providerHasHook() {
        return array(
            array('green'),
            array('eggs'),
            array('and'),
            array('ham'),
        );
    }

    /**
     * Ensures hasSubmission works as expected, and respects the &submitVar property
     * @return void
     */
    public function testHasSubmission() {
        $_POST = array('test' => true);
        $this->request->config['submitVar'] = 'submit-btn';
        $this->assertFalse($this->request->hasSubmission());
        $_POST['submit-btn'] = true;
        $this->assertTrue($this->request->hasSubmission());
    }

    /**
     * Ensure loadValidator actually loads the validation class
     * @return void
     */
    public function testLoadValidator() {
        $validator = $this->request->loadValidator();
        $this->assertInstanceOf('fiValidator',$validator);
    }

    /**
     * Ensure loadDictionary actually loads the dictionary class
     * @return void
     */
    public function testLoadDictionary() {
        $dictionary = $this->request->loadDictionary();
        $this->assertInstanceOf('fiDictionary',$dictionary);
    }

    /**
     * Ensure the success message is properly set as a placeholder
     * 
     * @param string $message
     * @param string $placeholderPrefix
     * @param string $successMessagePlaceholder
     * @dataProvider providerSetSuccessMessage
     */
    public function testSetSuccessMessage($message,$placeholderPrefix = 'fi.',$successMessagePlaceholder = '') {
        if (empty($successMessagePlaceholder)) $successMessagePlaceholder = $placeholderPrefix.'successMessage';
        
        $this->request->config['placeholderPrefix'] = $placeholderPrefix;
        $this->request->config['successMessagePlaceholder'] = $successMessagePlaceholder;
        $this->request->setSuccessMessage($message);
        
        $this->assertArrayHasKey($successMessagePlaceholder,$this->modx->placeholders);
        $this->assertEquals($message,$this->modx->placeholders[$successMessagePlaceholder]);
    }
    /**
     * @return array
     */
    public function providerSetSuccessMessage() {
        return array(
            array('A test success message.'),
            array('Badger badger badger.','mushroom.'),
            array('Leave the gun.','TakeTheCannoli.','sonny'),
        );
    }

    /**
     * Ensure convertMODXTags properly converts MODX tags to entities
     * @param string $string
     * @param string $expected
     * @dataProvider providerConvertMODXTags
     */
    public function testConvertMODXTags($string,$expected) {
        $result = $this->request->convertMODXTags($string);
        $this->assertEquals($expected,$result);
    }
    /**
     * @return array
     */
    public function providerConvertMODXTags() {
        return array(
            array('There\'s no crying in baseball!','There\'s no crying in baseball!'),
            array('I\'ll have [[what]] she\'s having.','I\'ll have &#91;&#91;what&#93;&#93; she\'s having.'),
        );
    }

    /**
     * Ensure setFieldsAsPlaceholders properly sets field data and adds the prefix
     * 
     * @param string $key
     * @param mixed $value
     * @param string $placeholderPrefix
     * @dataProvider providerSetFieldsAsPlaceholders
     */
    public function testSetFieldsAsPlaceholders($key,$value,$placeholderPrefix = 'fi.') {
        $this->request->config['placeholderPrefix'] = $placeholderPrefix;
        $this->request->loadDictionary();
        $this->request->dictionary->set($key,$value);
        $this->request->setFieldsAsPlaceholders();

        $this->assertArrayHasKey($placeholderPrefix.$key,$this->modx->placeholders);
        $this->assertEquals($value,$this->modx->placeholders[$placeholderPrefix.$key]);
    }
    /**
     * @return array
     */
    public function providerSetFieldsAsPlaceholders() {
        return array(
            array('tiger','Yo, Adrian!'),
            array('thorn','Soylent Green is People!','dr.'),
        );
    }
}