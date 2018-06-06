<?php
require_once dirname(dirname(__FILE__)) . '/index.class.php';

/**
 * Loads the home page.
 *
 * @package formit
 * @subpackage controllers
 */
class FormItHomeManagerController extends FormItBaseManagerController
{
    /** @var bool Whether or not a failure message was sent by this controller. */
    protected $isFailure = false;
    /** @var string The failure message, if existent, for this controller. */
    protected $failureMessage = '';


    public function process(array $scriptProperties = [])
    {

        if (isset($_GET['file']) && isset($_GET['formid'])) {
            $formid = (int)$_GET['formid'];
            $form = $this->modx->getObject('FormItForm', $formid);
            if (!$form) {
                $this->failureMessage = 'Form not found!';
                $this->isFailure = true;

                return;
            }
            $response = $form->downloadFile($_GET['file']);
            if ($response !== true) {
                $this->failureMessage = $response;
                $this->isFailure = true;

                return;
            }

        }

    }


    public function getPageTitle()
    {
        return $this->modx->lexicon('formit');
    }


    public function loadCustomCssJs()
    {
        $this->addJavascript($this->formit->config['jsUrl'] . 'mgr/widgets/forms.grid.js');
        $this->addJavascript($this->formit->config['jsUrl'] . 'mgr/widgets/forms-encryption.grid.js');
        $this->addJavascript($this->formit->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->formit->config['jsUrl'] . 'mgr/widgets/window.clean-forms.js');
        $this->addLastJavascript($this->formit->config['jsUrl'] . 'mgr/sections/home.js');
    }


    public function getTemplateFile()
    {
        return $this->formit->config['templatesPath'] . 'home.tpl';
    }
}