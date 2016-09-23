<?php
/**
 * Get list Forms
 *
 * @package FormIt
 * @subpackage processors
 */
class FormItGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'FormItForm';
    public $languageTopics = array('formit:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $form = $this->getProperty('form');
        if (!empty($form)) {
            $c->where(array('form' => $form));
        }
        
        $context_key = $this->getProperty('context_key');
        if (!empty($context_key)) {
            $c->where(array('context_key' => $context_key));
        }

        $startDate = $this->getProperty('startDate');
        if ($startDate != '') {
            $c->andCondition(array('date:>' => strtotime($startDate.' 00:00:00')));
        }

        $endDate = $this->getProperty('endDate');
        if ($endDate != '') {
            $c->andCondition(array('date:<' => strtotime($endDate.' 23:59:59')));
        }

        return $c;
    }
    public function prepareRow(xPDOObject $object)
    {
        $ff = $object->toArray();
        if ($ff['encrypted']){
            $ff['values'] = $object->decrypt($ff['values']);
        }
        
        return $ff;
    }
}
return 'FormItGetListProcessor';