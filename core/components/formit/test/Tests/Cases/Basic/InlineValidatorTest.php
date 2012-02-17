<?php
/**
 * @package formit-test
 */
/**
 * Creates a test that tests inline validators
 *
 * @package formit-test
 * @group Cases
 * @group Cases.Basic
 * @group Cases.Basic.Validation
 * @group Validation
 * @group InlineValidator
 */
class InlineValidatorTest extends FiTestCase {

    /**
     * Setup a basic form with only one field and a submit button
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->formit->config['validate'] = 'name:required,email:email:required';
        $this->formit->config['placeholderPrefix'] = 'fi.';
        $this->formit->config['submitVar'] = 'submit-btn';
        $this->formit->initialize('web');
        $_POST = array(
            'name' => 'Mr. Tester',
            'email' => 'test@example.com',
            'submit-btn' => 'Submit Contact Form',
        );
        $_REQUEST = $_POST;
    }

    /**
     * Ensure that validation fails for a non-existent field with an inline validator
     *
     * @param boolean $shouldPass
     * @param string $value
     * @dataProvider providerRequired
     */
    public function testRequired($shouldPass,$value) {
        $_POST['test:required'] = $value;
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :required validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
        $fields = $this->formit->request->dictionary->toArray();
        $this->assertArrayHasKey('test',$fields);
    }
    /**
     * @return array
     */
    public function providerRequired() {
        return array(
            array(true,'A test value'),
            array(true,'hello@example.com'),
            array(false,''),
        );
    }

}