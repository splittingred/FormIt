<?php
/**
 * @package formit-test
 */
/**
 * Creates a test for a form with a prehook that sets fields.
 *
 * @package formit-test
 * @group Cases
 * @group Cases.Basic
 * @group Cases.Basic.PreHook
 * @group Hooks
 */
class PreHookTest extends FiTestCase {

    /**
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->formit->config['validate'] = 'name:required,email:required';
        $this->formit->config['placeholderPrefix'] = 'fi.';
        $this->formit->config['submitVar'] = 'submit-btn';
        $this->formit->initialize('web');
        $_POST = array(
            'name' => 'John Doe',
            'email' => 'my@email.com',
            'submit-btn' => 'Submit Contact Form',
        );
        $_REQUEST = $_POST;
    }

    /**
     * Attempt to run a file-based preHook and set the value of a field
     * 
     * @return void
     */
    public function testSetValue() {
        $this->formit->config['preHooks'] = $this->formit->config['testsPath'].'hooks/pre/prehooktest.setvalue.php';
        $this->processForm();

        $val = $this->formit->preHooks->getValue('name');
        $success = strcmp($val,'TestPreValue') == 0;
        $this->assertTrue($success,'The preHook was not fired or did not set the value of the field.');
    }
}