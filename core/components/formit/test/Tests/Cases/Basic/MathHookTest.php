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
 * @group Cases.Basic.Math
 * @group Hooks
 */
class MathHookTest extends FiTestCase {
    /**
     * Setup a basic form with only one field and a submit button
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->formit->config['validate'] = 'name:required';
        $this->formit->config['placeholderPrefix'] = 'fi.';
        $this->formit->config['submitVar'] = 'submit-btn';
        $this->formit->config['hooks'] = 'math';
        $this->formit->initialize('web');
        $_POST = array(
            'name' => 'Mr. Tester',
            'submit-btn' => 'Submit Contact Form',
            'math' => 100,
            'op1' => 42,
            'op2' => 58,
            'operator' => '+',
        );
        $_REQUEST = $_POST;
    }

    /**
     * Give the math hook a correct answer and ensure that it passes
     *
     * @param mixed $operand1 The first field to send
     * @param mixed $operand2 The second field to send
     * @param string $operator The operator being performed
     * @param int $answer The expected answer
     * @return void
     * @dataProvider providerCorrectAnswer
     */
    public function testCorrectAnswer($operand1,$operand2,$operator,$answer) {
        $_POST['op1'] = $operand1;
        $_POST['op2'] = $operand2;
        $_POST['operator'] = $operator;
        $_POST['math'] = $answer;
        $this->processForm();

        $fields = $this->formit->request->dictionary->toArray();

        $success = $this->formit->postHooks->math($fields);
        $this->assertTrue($success,'The math hook failed to pass even when given a correct answer.');

        $errors = $this->formit->postHooks->getErrors();
        $success = empty($errors['math']);
        $this->assertTrue($success,'The math hook set an error placeholder even when given a correct answer.');
    }
    public function providerCorrectAnswer() {
        return array(
            array(20,50,'+',70),
            array('01',11,'+',12),
            array(-123,100,'-',-223),
        );
    }

    /**
     * Give the math hook an incorrect answer and ensure it fails and the error placeholder is set
     *
     * @param mixed $operand1 The first field to send
     * @param mixed $operand2 The second field to send
     * @param string $operator The operator being performed
     * @param int $answer The expected answer
     * @return void
     * @dataProvider providerIncorrectAnswer
     */
    public function testIncorrectAnswer($operand1,$operand2,$operator,$answer) {
        $_POST['op1'] = $operand1;
        $_POST['op2'] = $operand2;
        $_POST['operator'] = $operator;
        $_POST['math'] = $answer;
        $this->processForm();

        $fields = $this->formit->request->dictionary->toArray();

        $success = $this->formit->postHooks->math($fields);
        $this->assertTrue(!$success,'The math hook passed even when given a incorrect answer.');

        $errors = $this->formit->postHooks->getErrors();
        $success = !empty($errors['math']);
        $this->assertTrue($success,'The math hook failed to set the error placeholder on the math field when given a incorrect answer.');
    }
    public function providerIncorrectAnswer() {
        return array(
            array(20,50,'+',1233),
            array('',11,'+',220),
            array(-123,100,'-',444),
        );
    }
}