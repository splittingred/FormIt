<?php
/**
 * FormIt Connector
 *
 * @package formit
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$corePath = $modx->getOption('formit.core_path',null,$modx->getOption('core_path').'components/formit/');
require_once $corePath.'model/formit/formit.class.php';
$modx->formit = new FormIt($modx);

$modx->lexicon->load('formit:default');

/* handle request */
$path = $modx->getOption('processorsPath',$modx->formit->config,$corePath.'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));