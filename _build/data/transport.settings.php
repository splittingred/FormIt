<?php
/**
 * @package formit
 * @subpackage build
 */
$settings = array();

$settings['formit.core_path']= $modx->newObject('modSystemSetting');
$settings['formit.core_path']->fromArray(array(
    'key' => 'formit.core_path',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'formit',
    'area' => 'Paths',
),'',true,true);

$settings['formit.assets_path']= $modx->newObject('modSystemSetting');
$settings['formit.assets_path']->fromArray(array(
    'key' => 'formit.assets_path',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'formit',
    'area' => 'Paths',
),'',true,true);

$settings['formit.assets_url']= $modx->newObject('modSystemSetting');
$settings['formit.assets_url']->fromArray(array(
    'key' => 'formit.assets_url',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'formit',
    'area' => 'Paths',
),'',true,true);

return $settings;