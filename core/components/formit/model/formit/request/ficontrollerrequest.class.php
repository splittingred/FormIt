<?php
/**
 * @package formit
 */
require_once MODX_CORE_PATH . 'model/modx/modrequest.class.php';
/**
 * @package formit
 */
class FiControllerRequest extends modRequest {
    public $base = null;

    function __construct(Base &$base,array $config = array()) {
        $this->base =& $base;
        $this->config = array_merge(array(
            'action_key' => 'action',
            'action_default' => 'home',
        ),$config);
    }

    /**
     * Extends modRequest::handleRequest and loads the proper error handler and
     * action_key value.
     *
     * {@inheritdoc}
     */
    public function handleRequest() {
        $this->loadErrorHandler();

        /* save page to manager object. allow custom actionVar choice for extending classes. */
        $this->action = isset($_REQUEST[$this->config['action_key']]) ? $_REQUEST[$this->config['action_key']] : $this->config['action_default'];

        $modx =& $this->modx;
        $base =& $this->base;
        $viewHeader = include $this->base->config['corePath'].'controllers/mgr/header.php';

        $f = $this->base->config['corePath'].'controllers/mgr/'.$this->action.'.php';
        if (file_exists($f)) {
            $viewOutput = include $f;
        } else {
            $viewOutput = 'Action not found: '.$f;
        }

        return $viewHeader.$viewOutput;
    }
}