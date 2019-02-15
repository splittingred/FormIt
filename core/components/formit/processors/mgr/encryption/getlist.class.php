<?php

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

class FormItGetListProcessor extends modObjectGetListProcessor
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
    public $defaultSortField = 'form';

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortDirection = 'ASC';

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
     * @param xPDOQuery $criteria.
     * @return xPDOQuery.
     */
    public function prepareQueryBeforeCount(xPDOQuery $criteria)
    {
        $criteria->where([
            'context_key:IN' => $this->getAvailableContexts(),
        ]);

        $query = $this->getProperty('query');

        if (!empty($query)) {
            $criteria->where([
                'form:LIKE' => '%' . $query . '%'
            ]);
        }

        $criteria->groupby('form');
        $criteria->groupby('context_key');

        return $criteria;
    }

    /**
     * @access public.
     * @param xPDOObject $object.
     * @return Array.
     */
    public function prepareRow(xPDOObject $object)
    {
        return array_merge($object->toArray(), [
            'encrypted'     => $this->modx->getCount($this->classKey, [
                'form'          => $object->get('form'),
                'context_key'   => $object->get('context_key'),
                'encrypted'     => 1
            ]),
            'decrypted'     => $this->modx->getCount($this->classKey, [
                'form'          => $object->get('form'),
                'context_key'   => $object->get('context_key'),
                'encrypted'     => 0
            ])
        ]);
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

return 'FormItGetListProcessor';
