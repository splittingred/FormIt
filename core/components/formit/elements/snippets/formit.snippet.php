<?php
/**
 * @package formit
 */
require_once $modx->getOption('core_path').'components/formit/model/formit/formit.class.php';
$fi = new FormIt($modx,$scriptProperties);
$fi->initialize($modx->context->get('key'));

return '';