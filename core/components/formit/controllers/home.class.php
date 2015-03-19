<?php
/**
 * Loads the home page.
 *
 * @package formit
 * @subpackage controllers
 */
class FormItHomeManagerController extends FormItBaseManagerController {
    public function process(array $scriptProperties = array()) {

    }
    public function getPageTitle() { return $this->modx->lexicon('formit'); }
    public function loadCustomCssJs() {
        $this->addJavascript($this->formit->config['jsUrl'].'mgr/widgets/forms.grid.js');
        $this->addJavascript($this->formit->config['jsUrl'].'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->formit->config['jsUrl'].'mgr/sections/home.js');
    }
    public function getTemplateFile() { return $this->formit->config['templatesPath'].'home.tpl'; }
}