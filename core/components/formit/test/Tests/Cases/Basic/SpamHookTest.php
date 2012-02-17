<?php
/**
 * @package formit-test
 */
/**
 * Creates a test for the 'spam' hook, specifically for the StopForumSpam checking
 *
 * @package formit-test
 * @group Cases
 * @group Cases.Basic
 * @group Cases.Basic.Spam
 * @group Hooks
 */
class SpamHookTest extends FiTestCase {
    /**
     * Setup a basic form with only one field and a submit button
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->formit->config['validate'] = 'name:required';
        $this->formit->config['placeholderPrefix'] = 'fi.';
        $this->formit->config['submitVar'] = 'submit-btn';
        $this->formit->initialize('web');
        $_POST = array(
            'name' => 'Mr. Tester',
            'submit-btn' => 'Submit Contact Form',
            'spam' => '',
        );
        $_REQUEST = $_POST;
    }
    
    /**
     * Test a good IP that will always pass spam checking
     * 
     * @return void
     */
    public function testGoodIp() {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $this->formit->config['hooks'] = 'spam';
        $this->formit->config['spamCheckIp'] = true;
        $this->processForm();
        $fields = $this->formit->request->dictionary->toArray();

        $this->formit->postHooks->spam($fields);
        $errors = $this->formit->postHooks->getErrors();
        $success = empty($errors['email']);

        $this->assertTrue($success);
    }

    /**
     * Test a spammers IP and ensure the spam filter sets the right error placeholder
     *
     * @return void
     */
    public function testBadIp() {
        $_SERVER['REMOTE_ADDR'] = '109.230.220.83';
        $this->formit->config['hooks'] = 'spam';
        $this->formit->config['spamCheckIp'] = true;
        $this->processForm();
        $fields = $this->formit->request->dictionary->toArray();

        $this->formit->postHooks->spam($fields);
        $errors = $this->formit->postHooks->getErrors();
        $success = !empty($errors['email']);

        $this->assertTrue($success);
    }

    /**
     * Test the email protection for the spam checker on a good email that should always pass
     *
     * @return void
     */
    public function testGoodEmail() {
        $_POST['email'] = 'hello@modx.com';
        $this->formit->config['hooks'] = 'spam';
        $this->processForm();
        $fields = $this->formit->request->dictionary->toArray();

        $this->formit->postHooks->spam($fields);
        $errors = $this->formit->postHooks->getErrors();
        $success = empty($errors['email']);

        $this->assertTrue($success);
    }

    /**
     * Test the email protection for the spam checker on a spammer's email address
     *
     * @return void
     */
    public function testBadEmail() {
        $_POST['email'] = 'dowsWoows@00.msk.ru';
        $this->formit->config['hooks'] = 'spam';
        $this->processForm();
        $fields = $this->formit->request->dictionary->toArray();

        $this->formit->postHooks->spam($fields);
        $errors = $this->formit->postHooks->getErrors();
        $success = !empty($errors['email']);

        $this->assertTrue($success);
    }
}
