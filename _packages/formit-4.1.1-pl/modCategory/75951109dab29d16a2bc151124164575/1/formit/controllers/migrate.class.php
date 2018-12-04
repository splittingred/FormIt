<?php
require_once dirname(dirname(__FILE__)) . '/index.class.php';
/**
 * Loads the home page.
 *
 * @package modxminify
 * @subpackage controllers
 */
class FormItMigrateManagerController extends FormItBaseManagerController
{
    public function process(array $scriptProperties = array())
    {

    }
    public function getPageTitle()
    {
        return $this->modx->lexicon('formit.migrate');
    }
    public function loadCustomCssJs()
    {
        $this->addJavascript($this->formit->config['jsUrl'].'mgr/widgets/migrate.panel.js');
        $this->addLastJavascript($this->formit->config['jsUrl'].'mgr/sections/migrate.js');
    }

    public function getTemplateFile()
    {
        return $this->formit->config['templatesPath'].'migrate.tpl';
    }
}
