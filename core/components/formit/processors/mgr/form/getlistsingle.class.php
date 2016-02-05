<?php
/**
 * Get list Forms
 *
 * @package FormIt
 * @subpackage processors
 */
class FormItGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'FormItForm';
    public $languageTopics = array('formit:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->groupby('form');
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $ff = $object->toArray();
        $ff['encrypted'] = $this->modx->getCount($this->classKey, array('form' => $ff['form'],'encrypted' => 1));
        $ff['total'] = $this->modx->getCount($this->classKey, array('form' => $ff['form']));
        return $ff;
    }
}
return 'FormItGetListProcessor';