<?php

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

class FormItDecryptProcessor extends modProcessor
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

    /**
     * @access public.
     * @return Mixed.
     */
    public function process()
    {
        $amount = 0;

        $criteria = [
            'form'              => $this->getProperty('form'),
            'context_key:IN'    => $this->getAvailableContexts(),
            'encrypted'         => 1
        ];

        foreach ($this->modx->getCollection($this->classKey, $criteria) as $object) {
            $object->set('values', $object->decrypt($object->get('values'), $object->get('type')));
            $object->set('encrypted', 0);
            $object->set('encryption_type', 0);

            if ($object->save()) {
                $amount++;
            }
        }

        return $this->success($this->modx->lexicon('formit.decrypt_success', [
            'amount' => $amount
        ]));
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getAvailableContexts()
    {
        $contexts = ['-'];

        foreach ($this->modx->getCollection('modContext') as $context) {
            $contexts[] = $context->get('key');
        }

        return $contexts;
    }
}

return 'FormItDecryptProcessor';
