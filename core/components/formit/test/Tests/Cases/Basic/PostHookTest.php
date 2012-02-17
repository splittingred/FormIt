<?php
/**
 * @package formit-test
 */
/**
 * Creates a test for a form with a posthook with various actions.
 *
 * @package formit-test
 * @group Cases
 * @group Cases.Basic.PostHook
 * @group Hooks
 */
class PostHookTest extends FiTestCase {
    /**
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->formit->config['validate'] = 'name:required';
        $this->formit->config['placeholderPrefix'] = 'fi.';
        $this->formit->config['submitVar'] = 'submit-btn';
        $this->formit->config['hooks'] = '';
        $this->formit->initialize('web');
        $_POST = array(
            'name' => 'John Doe',
            'email' => 'my@email.com',
            'submit-btn' => 'Submit Contact Form',
        );
        $_REQUEST = $_POST;
    }

    /**
     * Attempt to set a redirect URL in a hook
     * @return void
     */
    public function testSetRedirectUrl() {
        $this->formit->config['hooks'] = $this->formit->config['testsPath'].'hooks/post/posthooktest.redirecturl.php';
        $this->processForm();
        $url = $this->formit->postHooks->getRedirectUrl();

        $success = strcmp($url,'http://modx.com/') == 0;
        $this->assertTrue($success,'$hook->setRedirectUrl() failed in the post hook.');
    }

    /**
     * Ensure that a failed hook stops the request cycle and prevents another hook from executing
     * @return void
     * @depends testSetRedirectUrl
     */
    public function testFailedHook() {
        $this->formit->config['hooks'] = $this->formit->config['testsPath'].'hooks/post/posthooktest.fail.php';
        $this->formit->config['hooks'] .= ','.$this->formit->config['testsPath'].'hooks/post/posthooktest.redirecturl.php';
        $this->processForm();

        $success = empty($this->modx->placeholders['fi.success']);
        $this->assertTrue($success,'The fi.success placeholder was set to true when it should not have ever been set, due to a failed hook.');


        $url = $this->formit->postHooks->getRedirectUrl();
        $success = empty($url);
        $this->assertTrue($success,'The failed hook did not stop propagation along the hook chain and set the redirectUrl, which never should have occurred.');
    }

    /**
     * Test fiHooks::gatherFields method, specifically with checkbox values
     * @return void
     */
    public function testGatherFields() {
        $this->processForm();
        $this->formit->postHooks->fields['checkbox'] = array(1,2);

        $this->formit->postHooks->gatherFields();
        $this->assertEquals($this->modx->placeholders['fi.checkbox'],'1,2');
    }

    /**
     * Test $hook->setValues from within a postHook
     * @return void
     */
    public function testSetValues() {
        $this->formit->config['hooks'] = $this->formit->config['testsPath'].'hooks/post/posthooktest.setvalues.php';
        $this->processForm();
        $values = $this->formit->postHooks->getValues();
        $success = strcmp($values['name'],'John Doe') == 0 && strcmp($values['email'],'john.doe@fake-emails.com') == 0;
        $this->assertTrue($success,'$hook->setValues() failed in the post hook.');
    }

    /**
     * Test $hook->addError from within a postHook
     * @return void
     */
    public function testAddError() {
        $this->formit->config['hooks'] = $this->formit->config['testsPath'].'hooks/post/posthooktest.adderror.php';
        $this->processForm();
        $this->assertTrue($this->formit->postHooks->hasErrors(),'$hook->addError did not add any errors.');
        
        $errors = $this->formit->postHooks->getErrors();
        $success = strcmp($errors['name'],'Please use a real name.') == 0;
        $this->assertTrue($success,'$hook->addError() failed to add the correct error message in the post hook.');
    }
}