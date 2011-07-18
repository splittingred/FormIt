<?php
/**
 * @package formit-test
 */
/**
 * Creates a test for a form that submits only a single field, "name", as its value.
 *
 * @package formit-test
 * @group Cases.Basic
 */
class SingleFieldTest extends FiTestCase {

    /**
     * Setup a basic form with only one field
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->formit->config['validate'] = 'name:required';
        $this->formit->config['placeholderPrefix'] = 'fi.';
        $this->formit->initialize('web');
        $_POST = array(
            'name' => 'Mr. Tester',
        );
        $_REQUEST = $_POST;
    }

    /**
     * Ensure that the placeholder "fi.name" was properly set
     * @return void
     */
    public function testPlaceholderSet() {
        $this->processForm();
        $set = !empty($this->modx->placeholders['fi.name']) && $this->modx->placeholders['fi.name'] == 'Mr. Tester';
        $this->assertTrue($set,'The placeholder "fi.name" was not set to "Mr. Tester"');
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
}