<?php
/**
 * Get list Forms
 *
 * @package FormIt
 * @subpackage processors
 */
class FormItEncryptProcessor extends modObjectGetListProcessor
{
    public $classKey = 'FormItForm';
    public $languageTopics = array('formit:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->where(array(
            'form' => $this->getProperty('form'),
            'encrypted' => 0
        ));
        return $c;
    }

    public function prepareRow(xPDOObject $object)
    {
        $object->set('encrypted', 1);
        $values = $object->get('values');
        $object->set('values', $object->encrypt($values));
        $object->save();
        $ff = $object->toArray();
        return $ff;
    }
}
return 'FormItEncryptProcessor';
