<?php
/**
 * @package formit-test
 */
/**
 * Creates a test for the AutoResponder snippet hook
 *
 * @package formit-test
 * @group Cases
 * @group Cases.AutoResponder
 * @group Cases.AutoResponder.Basic
 * @group Hooks
 */
class AutoResponderBasicTest extends FiTestCase {
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
            'hooks' => $this->formit->config['snippetsPath'].'snippet.formitautoresponder.php',

            'fiarSubject' => 'Unit Test Email',
            'fiarToField' => 'email',
            'fiarTpl' => $this->formit->config['testsPath'].'Data/Chunks/emailhook.chunk.tpl',
            'fiarMultiSeparator' => ',',
            'fiarMultiWrapper' => '[[+value]]',
        ));
        $this->formit->initialize('web');
        $_POST = array(
            'name' => 'Mr. Tester',
            'email' => 'test@example.com',
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
        $errors = $this->formit->postHooks->getErrors();
        $success = empty($errors);
        $this->assertTrue($success);
    }
}