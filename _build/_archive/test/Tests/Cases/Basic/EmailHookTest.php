<?php
/**
 * @package formit-test
 */
/**
 * Creates a test for the 'math' hook
 *
 * @package formit-test
 * @group Cases
 * @group Cases.Basic
 * @group Cases.Basic.Email
 * @group Hooks
 */
class EmailHookTest extends FiTestCase {
    /**
     * Setup a basic form with only one field and a submit button
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->formit->setOptions(array(
            'validate' => 'name:required',
            'placeholderPrefix' => 'fi.',
            'submitVar' => 'submit-btn',
            'hooks' => 'email',
            'emailSubject' => 'Unit Test Email',
            'emailTo' => 'test@email.com',
            'emailToName' => 'Test User',
            'emailMultiSeparator' => ',',
            'emailTpl' => $this->formit->config['testsPath'].'Data/Chunks/emailhook.chunk.tpl',
        ));
        $this->formit->initialize('web');
        $_POST = array(
            'name' => 'Mr. Tester',
            'submit-btn' => 'Submit Contact Form',
            'checkboxes' => array(
                0 => 'one',
                1 => 'two',
            ),
            'named' => array(
                'three' => 'THREE',
                'four' => 'FOUR',
            ),
        );
        $_REQUEST = $_POST;
    }

    public function testArrayInEmail() {
        $this->processForm();
        $fields = $this->formit->request->dictionary->toArray();
        $success = $this->formit->postHooks->email($fields);      
        $this->assertTrue($success,'The email hook failed.');
    }
}