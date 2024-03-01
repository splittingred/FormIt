<?php

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

class FormItFormRemoveProcessor extends modObjectRemoveProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $classKey = 'FormItForm';

    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['formit:default'];

    /**
     * @access public.
     * @var String.
     */
    public $objectType = 'formit.form';

    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('formit', 'FormIt', $this->modx->getOption('formit.core_path', null, $this->modx->getOption('core_path') . 'components/formit/') . 'model/formit/');

        return parent::initialize();
    }
}

return 'FormItFormRemoveProcessor';
