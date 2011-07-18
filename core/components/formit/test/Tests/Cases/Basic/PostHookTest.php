<?php
/**
 * @package formit-test
 */
/**
 * Creates a test for a form with a posthook with various actions.
 *
 * @package formit-test
 * @group Cases.Basic
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
     * Test fiHooks::gatherFields method, specifically with checkbox values
     * @return void
     */
    public function testGatherFields() {
        $this->processForm();
        $this->formit->postHooks->fields['checkbox'] = array(1,2);

        $this->formit->postHooks->gatherFields();
        $this->assertEquals($this->modx->placeholders['fi.checkbox'],'1,2');
    }
}