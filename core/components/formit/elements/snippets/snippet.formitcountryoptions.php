<?php
/**
 * Automatically generates and outputs a country list for usage in forms
 * 
 * @package formit
 */
require_once $modx->getOption('formit.core_path',null,$modx->getOption('core_path').'components/formit/').'model/formit/formit.class.php';
$fi = new FormIt($modx,$scriptProperties);

$tpl = $modx->getOption('tpl',$scriptProperties,'option');
$selected = $modx->getOption('selected',$scriptProperties,'');
$useIsoCode = $modx->getOption('useIsoCode',$scriptProperties,true);
$selectedAttribute = $modx->getOption('selectedAttribute',$scriptProperties,' selected="selected"');

$selectedKey = $useIsoCode ? 'countryKey' : 'countryName';
$output = '';
$countries = include $fi->config['includesPath'].'countries.inc.php';
foreach ($countries as $countryKey => $countryName) {
    $countryArray = array(
        'text' => $countryName,
        'value' => $countryKey,
        'selected' => '',
    );
    if ($selected == $$selectedKey) {
        $countryArray['selected'] = $selectedAttribute;
    }
    $output[] = $fi->getChunk($tpl,$countryArray);
}

$output = implode("\n",$output);
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,'');
if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder,$output);
    $output = '';
}
return $output;