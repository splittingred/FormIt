<?php
/**
 * Remove an Form.
 * 
 * @package formit
 * @subpackage processors
 */
class FormItFormRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'FormItForm';
    public $languageTopics = array('formit:default');
}
return 'FormItFormRemoveProcessor';