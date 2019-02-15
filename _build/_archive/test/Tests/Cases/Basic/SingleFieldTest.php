<?php
/**
 * @package formit-test
 */
/**
 * Creates a test for a form that submits only a single field, "name", as its value.
 *
 * @package formit-test
 * @group Cases
 * @group Cases.Basic
 * @group Cases.Basic.SingleField
 */
class SingleFieldTest extends FiTestCase {

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
        );
        $_REQUEST = $_POST;
    }

    /**
     * Ensure that the placeholder "fi.name" was properly set on a form
     * @return void
     */
    public function testPlaceholderSet() {
        $this->formit->config['clearFieldsOnSuccess'] = false;
        $this->processForm();
        $set = !empty($this->modx->placeholders['fi.name']) && $this->modx->placeholders['fi.name'] == 'Mr. Tester';
        $this->assertTrue($set,'The placeholder "fi.name" was not set to "Mr. Tester". Was set to: '.$this->modx->placeholders['fi.name']);
    }

    /**
     * Ensure that validation passes for name:required string
     */
    public function testRequiredName() {
        $this->processForm();
        $hasNoErrors = !$this->formit->request->validator->hasErrors();
        $this->assertTrue($hasNoErrors,'The validation string name:required did not pass.');
    }

    /**
     * Ensure that validation fails for a non-existent field
     *
     * @return void
     */
    public function testValidationForNonExistentField() {
        $this->formit->config['validate'] = 'email:required';
        $this->processForm();

        $hasErrors = $this->formit->request->validator->hasErrors();
        $this->assertTrue($hasErrors,'The email:required validation passed, which should not have occurred.');
    }

    /**
     * Ensure the form does not submit if the submitVar POST var is not sent
     * @return void
     */
    public function testEmptySubmitVar() {
        unset($_POST['submit-btn']);
        $this->processForm();

        $request = $this->formit->loadRequest();
        $notSubmitted = !$request->hasSubmission();
        $this->assertTrue($notSubmitted,'The &submitVar property was ignored, and FormIt assumed incorrectly this was a POST submission.');
    }

    /**
     * Ensure the form submits if no &submitVar property is set
     * @return void
     */
    public function testNoSubmitVarProperty() {
        unset($_POST['submit-btn']);
        $this->formit->config['submitVar'] = '';
        $this->processForm();

        $request = $this->formit->loadRequest();
        $submitted = $request->hasSubmission();
        $this->assertTrue($submitted,'FormIt did not submit the form, incorrectly looking for a submitVar when one was not needed');
    }

    /**
     * Ensure the &clearFieldsOnSuccess property is correctly working
     * 
     * @param boolean $clearFieldsOnSuccess Whether or not to clear the fields on a successful form submission
     * @param boolean $fieldShouldExist Whether or not the field should be set
     * @dataProvider providerClearFieldsOnSuccess
     */
    public function testClearFieldsOnSuccess($clearFieldsOnSuccess,$fieldShouldExist) {
        $this->formit->config['clearFieldsOnSuccess'] = $clearFieldsOnSuccess;
        unset($this->modx->placeholders['fi.name']);
        $this->processForm();

        $nameIsSet = !empty($this->modx->placeholders['fi.name']);
        $success = $fieldShouldExist ? $nameIsSet : !$nameIsSet;
        $this->assertTrue($success,'The property clearFieldsOnSuccess was not respected with a value of: '.($clearFieldsOnSuccess ? 1 : 0));
    }
    /**
     * Data provider for update processor test
     * @return array
     */
    public function providerClearFieldsOnSuccess() {
        return array(
            array(true,false),
            array(false,true),
        );
    }

    /**
     * Ensure &errTpl works as expected
     * 
     * @param string $tpl The tpl to use for the &errTpl value
     * @param string $lookFor The string to look for in the output
     * @return void
     * @dataProvider providerErrTpl
     */
    public function testErrTpl($tpl,$lookFor) {
        $this->formit->config['errTpl'] = $tpl;
        $_POST['name'] = '';
        $this->processForm();

        $errors = $this->formit->request->validator->getErrors();
        $success = false;
        if (!empty($errors['name'])) {
            if (!empty($lookFor)) {
                if (strpos($errors['name'],$lookFor) !== false) {
                    $success = true;
                }
            } else {
                $success = true;
            }
        }
        $this->assertTrue($success,'The property &errTpl was not respected with the following value: '.$tpl);
    }
    public function providerErrTpl() {
        return array(
            array('<div class="error">[[+error]]</div>','<div class="error">'),
            array('<span>[[+error]]</span>','<span>'),
            array('[[+error]]',''),
        );
    }

    /**
     * Ensure &placeholderPrefix works as expected
     * @param string $prefix The prefix to test with
     * @return void
     * @dataProvider providerPlaceholderPrefix
     */
    public function testPlaceholderPrefix($prefix) {
        $this->formit->config['clearFieldsOnSuccess'] = false;
        $this->formit->config['placeholderPrefix'] = $prefix;
        $this->processForm();
        $set = !empty($this->modx->placeholders[$prefix.'name']) && $this->modx->placeholders[$prefix.'name'] == 'Mr. Tester';
        $this->assertTrue($set,'The property &placeholderPrefix was not respected with the following value: '.$prefix);
    }
    public function providerPlaceholderPrefix($prefix) {
        return array(
            array('fi.'),
            array('formit-'),
            array('fi'),
            array(''),
        );
    }
}