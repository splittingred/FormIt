<?php
/**
 * Get list Forms
 *
 * @package FormIt
 * @subpackage processors
 */
class FormItDecryptProcessor extends modObjectGetListProcessor
{
    public $classKey = 'FormItForm';
    public $languageTopics = array('formit:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';

    public function initialize()
    {
        $this->setProperty('limit', 0);
        return parent::initialize();
    }

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->where(array(
            'form' => $this->getProperty('form'),
            'encrypted' => 1
        ));
        return $c;
    }

    public function prepareRow(xPDOObject $object)
    {
        $object->set('encrypted', 0);
        $values = $object->get('values');
        $type = $object->get('encryption_type');
        $object->set('values', $object->decrypt($values, $type));
        $object->save();
        $ff = $object->toArray();
        return $ff;
    }
}
return 'FormItDecryptProcessor';
