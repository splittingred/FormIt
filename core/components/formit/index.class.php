<?php
require_once dirname(__FILE__) . '/model/formit/formit.class.php';
/**
 * @package formit
 */

abstract class FormItBaseManagerController extends modExtraManagerController {
    /** @var FormIt $bxrextra */
    public $formit;
    public function initialize() {
        $this->formit = new FormIt($this->modx);

        $this->addCss($this->formit->config['cssUrl'].'mgr.css');
        $this->addJavascript($this->formit->config['jsUrl'].'mgr/formit.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            FormIt.config = '.$this->modx->toJSON($this->formit->config).';
            FormIt.config.connector_url = "'.$this->formit->config['connectorUrl'].'";
        });
        </script>');
        return parent::initialize();
    }
    public function getLanguageTopics() {
        return array('formit:mgr');
    }
    public function checkPermissions() { return true;}
}

class IndexManagerController extends FormItBaseManagerController {
    public static function getDefaultController() { return 'home'; }
}