<?php
/**
 * @package formit-test
 */
/**
 * Extends the basic PHPUnit TestCase class to provide FormIt specific methods
 *
 * @package formit-test
 */
class FiTestCase extends PHPUnit_Framework_TestCase {
    /**
     * @var modX $modx
     */
    protected $modx = null;
    /**
     * @var FormIt $formit
     */
    protected $formit = null;

    /**
     * Ensure all tests have a reference to the MODX and FormIt objects
     */
    public function setUp() {
        $this->modx = FiTestHarness::_getConnection();
        $fiCorePath = $this->modx->getOption('formit.core_path',null,$this->modx->getOption('core_path',null,MODX_CORE_PATH).'components/formit/');
        require_once $fiCorePath.'model/formit/formit.class.php';
        $this->formit = new FormIt($this->modx);
        /* set this here to prevent emails/headers from being sent */
        $this->formit->inTestMode = true;
        /* make sure to reset MODX placeholders so as not to keep placeholder data across tests */
        $this->modx->placeholders = array();
    }

    /**
     * Remove reference at end of test case
     */
    public function tearDown() {
        $this->modx = null;
        $this->formit = null;
    }

    /**
     * Go ahead and send the form through the request handler. Used as a shortcut method.
     * @return void
     */
    public function processForm() {
        $request = $this->formit->loadRequest();
        $fields = $request->prepare();
        $request->handle($fields);
    }
}