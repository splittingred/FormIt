<?php

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

abstract class FormItBaseManagerController extends modExtraManagerController
{
    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('formit', 'FormIt', $this->modx->getOption('formit.core_path', null, $this->modx->getOption('core_path') . 'components/formit/') . 'model/formit/');

        $this->addCss($this->modx->formit->config['css_url'] . 'mgr/formit.css');

        $this->addJavascript($this->modx->formit->config['js_url'] . 'mgr/formit.js');

        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                MODx.config.help_url = "' . $this->modx->formit->getHelpUrl() . '";
                
                FormIt.config = ' . $this->modx->toJSON(array_merge($this->modx->formit->config, [
                    'branding_url'      => $this->modx->formit->getBrandingUrl(),
                    'branding_url_help' => $this->modx->formit->getHelpUrl()
                ])) . ';
            });
        </script>');

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getLanguageTopics()
    {
        return $this->modx->formit->config['lexicons'];
    }

    /**
     * @access public.
     * @returns Boolean.
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('formit');
    }
}

class IndexManagerController extends FormItBaseManagerController
{
    /**
     * @access public.
     * @return String.
     */
    public static function getDefaultController()
    {
        return 'home';
    }
}
