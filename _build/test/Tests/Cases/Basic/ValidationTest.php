<?php
/**
 * @package formit-test
 */
/**
 * Creates a test that tests most validation parameters
 *
 * @package formit-test
 * @group Cases
 * @group Cases.Basic
 * @group Cases.Basic.Validation
 * @group Validation
 */
class ValidationTest extends FiTestCase {

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
     * Ensure that validation fails for a non-existent field
     *
     * @param boolean $shouldPass
     * @param string $key
     * @dataProvider providerRequired
     */
    public function testRequired($shouldPass,$key) {
        $this->formit->config['validate'] = $key.':required';
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :required validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
    }
    public function providerRequired() {
        return array(
            array(true,'name'),
            array(true,'email'),
            array(false,'epistemology'),
        );
    }

    /**
     * Ensure that validation fails for email fields
     *
     * @param boolean $shouldPass
     * @param string $email
     * @dataProvider providerEmail
     */
    public function testEmail($shouldPass,$email) {
        $this->formit->config['validate'] = 'email:email';
        $_POST['email'] = $email;
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :email validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
    }
    public function providerEmail() {
        return array(
            array(true,'test@example.com'),
            array(true,'a.good.email@mail.com'),
            array(false,'no.anything'),
            array(false,'no.tld@'),
            array(false,'bad.tld@dsacxz'),
        );
    }

    /**
     * Test :blank validator
     *
     * @param boolean $shouldPass
     * @param string $value
     * @dataProvider providerBlank
     */
    public function testBlank($shouldPass,$value) {
        $_POST['boo'] = $value;
        $this->formit->config['validate'] = 'boo:blank';
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :blank validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
    }
    public function providerBlank() {
        return array(
            array(true,''),
            array(false,'space'),
            array(false,' '),
            array(false,"\n"),
            array(false,"\r"),
        );
    }

    /**
     * Test :minLength provider
     * 
     * @param boolean $shouldPass
     * @param string $value
     * @param string $minLength
     * @dataProvider providerMinLength
     */
    public function testMinLength($shouldPass,$value,$minLength) {
        $_POST['smallers'] = $value;
        $this->formit->config['validate'] = 'smallers:minLength=^'.$minLength.'^';
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :minLength validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
    }
    public function providerMinLength() {
        return array(
            array(true,'1234567890',10),
            array(true,'abcdefghijklmnopqrstuvwxyz',10),
            array(true,'a test string with lots of stuff',10),
            array(false,'',10),
            array(true,'',0),
            array(false,'',1),
        );
    }

    /**
     * Test :maxLength provider
     *
     * @param boolean $shouldPass
     * @param string $value
     * @param string $maxLength
     * @dataProvider providerMaxLength
     */
    public function testMaxLength($shouldPass,$value,$maxLength) {
        $_POST['largers'] = $value;
        $this->formit->config['validate'] = 'largers:maxLength=^'.$maxLength.'^';
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :maxLength validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
    }
    public function providerMaxLength() {
        return array(
            array(true,'',10),
            array(true,'a test string with lots of stuff',100),
            array(true,'',0),
            array(false,'abcdefghijklmnopqrstuvwxyz',10),
            array(false,'z',0),
        );
    }

    /**
     * Test :minValue provider
     *
     * @param boolean $shouldPass
     * @param int|string $value
     * @param int $minValue
     * @dataProvider providerMinValue
     */
    public function testMinValue($shouldPass,$value,$minValue) {
        $_POST['smallers'] = $value;
        $this->formit->config['validate'] = 'smallers:minValue=^'.$minValue.'^';
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :minValue validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
    }
    public function providerMinValue() {
        return array(
            array(true,11,10),
            array(true,'11',10),
            array(true,0,0),
            array(false,1,10),
            array(false,'',10),
            array(false,'abc',10),
        );
    }

    /**
     * Test :maxValue provider
     *
     * @param boolean $shouldPass
     * @param int|string $value
     * @param int $maxValue
     * @dataProvider providerMaxValue
     */
    public function testMaxValue($shouldPass,$value,$maxValue) {
        $_POST['boom'] = $value;
        $this->formit->config['validate'] = 'boom:maxValue=^'.$maxValue.'^';
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :maxValue validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
    }
    public function providerMaxValue() {
        return array(
            array(true,9,10),
            array(true,'9',10),
            array(true,0,0),
            array(true,'',10),
            array(true,'abc',10),
            array(false,11,10),
        );
    }

    /**
     * Test :contains provider
     *
     * @param boolean $shouldPass
     * @param string $needle
     * @param string $haystack
     * @dataProvider providerContains
     */
    public function testContains($shouldPass,$needle,$haystack) {
        $_POST['looky'] = $haystack;
        $this->formit->config['validate'] = 'looky:contains=^'.$needle.'^';
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :contains validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
    }
    public function providerContains() {
        return array(
            array(true,'abc','abcdefghijklmnopqrstuvxyz'),
            array(true,'t','test'),
            array(true,'','test'),
            array(true,' ','A test string'),
            array(true,'word'," A word\ninside"),
            array(false,'word'," A wo\nrd inside"),
            array(false,'123','abcdef'),
            array(false,0,'abcdef'),
        );
    }

    /**
     * Test :range provider
     *
     * @param boolean $shouldPass
     * @param string|int $value
     * @param string $range
     * @dataProvider providerRange
     */
    public function testRange($shouldPass,$value,$range) {
        $_POST['firing-range'] = $value;
        $this->formit->config['validate'] = 'firing-range:range=^'.$range.'^';
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :range validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
    }
    public function providerRange() {
        return array(
            array(true,5,'0-10'),
            array(true,0,'0-10'),
            array(true,10,'0-10'),
            array(true,0,'0-0'),
            array(false,'','0-0'),
            array(false,33,'0-10'),
            array(false,'-1','0-10'),
            array(false,1,'0-0'),
        );
    }

    /**
     * Test :isNumber provider
     *
     * @param boolean $shouldPass
     * @param string|int $value
     * @dataProvider providerIsNumber
     */
    public function testIsNumber($shouldPass,$value) {
        $_POST['holidays'] = $value;
        $this->formit->config['validate'] = 'holidays:isNumber';
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :isNumber validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
    }
    public function providerIsNumber() {
        return array(
            array(true,1),
            array(true,0),
            array(true,'1'),
            array(true,' 1'),
            array(true,'1 '),
            array(true,''),
            array(false,'abc'),
            array(false,'one'),
        );
    }

    /**
     * Test :isDate provider
     *
     * @param boolean $shouldPass
     * @param string $value
     * @dataProvider providerIsDate
     */
    public function testIsDate($shouldPass,$value) {
        $_POST['birthday'] = $value;
        $this->formit->config['validate'] = 'birthday:isDate';
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :isDate validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
    }
    public function providerIsDate() {
        return array(
            array(true,'03/07/2009'),
            array(true,'03-07-2009'),
            array(true,'2009/03/07'),
            array(true,'2009-03-07'),
            array(true,'07.03.09'),
            array(true,'Mar 7 2009'),
            array(true,'Mar 7, 2009'),
            array(true,'March 7th 2009'),
            array(true,'March 7th, 2009'),
            array(true,'+1 day'),
            array(true,'now'),
            array(true,'last Tuesday'),
            array(true,'June 2008'),
            array(true,'May.9,78'),
            array(false,'z232sdaczx'),
        );
    }

    /**
     * Test :islowercase provider
     *
     * @param boolean $shouldPass
     * @param string|int $value
     * @dataProvider providerIsLowerCase
     */
    public function testIsLowerCase($shouldPass,$value) {
        $_POST['case_name'] = $value;
        $this->formit->config['validate'] = 'case_name:islowercase';
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :islowercase validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
    }
    public function providerIsLowerCase() {
        return array(
            array(true,'abc'),
            array(true,'1'),
            array(true,123),
            array(true,'a b c'),
            array(false,'aBc'),
            array(false,'ABC'),
            array(false,'aaaaaaaaC'),
        );
    }

    /**
     * Test :isuppercase provider
     *
     * @param boolean $shouldPass
     * @param string|int $value
     * @dataProvider providerIsUpperCase
     */
    public function testIsUpperCase($shouldPass,$value) {
        $_POST['case_name'] = $value;
        $this->formit->config['validate'] = 'case_name:isuppercase';
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :isuppercase validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
    }
    public function providerIsUpperCase() {
        return array(
            array(true,'ABC'),
            array(true,'A B C'),
            array(true,123),
            array(true,'1'),
            array(false,'aBc'),
            array(false,'abc'),
            array(false,'AAAAAAAAAAc'),
            array(false,'AAAAAAcAAAA'),
        );
    }

    /**
     * @param boolean $shouldPass
     * @param string $value
     * @param string $regexp
     * @dataProvider providerRegexp
     */
    public function testRegexp($shouldPass,$value,$regexp) {
        $_POST['case_name'] = $value;
        $this->formit->config['validate'] = 'case_name:regexp=^'.$regexp.'^';
        $this->processForm();
        $passed = !$this->formit->request->validator->hasErrors();
        $this->assertEquals($shouldPass,$passed,'The :regexp validation failed, which should not have occurred: '.print_r($this->formit->request->validator->errors,true));
    }
    public function providerRegexp() {
        return array(
            array(true,'abcdef','/^bcd/'),
            array(true,'This is written in PHP, a programming language','/php/i'),
            array(false,'This is not written in LOLCode, o hai','/php/i'),
        );
    }
}