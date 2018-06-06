<?php
/**
 * FormIt Cronjob
 *
 * @package formit
 */
if(!(php_sapi_name() === 'cli')) {
    header("HTTP/1.1 400 Bad Request");
    print 'Only runnable from CLI.';
    exit;
}
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$corePath = $modx->getOption('formit.core_path',null,$modx->getOption('core_path').'components/formit/');
require_once $corePath.'model/formit/formit.class.php';
$modx->formit = new FormIt($modx);

$modx->lexicon->load('formit:default');

/* handle request */
$path = $modx->getOption('processorsPath', $modx->formit->config, $corePath.'processors/');
$response = $modx->runProcessor('mgr/form/clean', [], [
    'processors_path' => $path
]);

if ($response->isError()) {
    print $response->getMessage();
}