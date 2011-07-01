<?php
/**
 * Automatically generates and outputs a U.S. state list for usage in forms
 * 
 * @package formit
 */
require_once $modx->getOption('formit.core_path',null,$modx->getOption('core_path').'components/formit/').'model/formit/formit.class.php';
$fi = new FormIt($modx,$scriptProperties);

$tpl = $modx->getOption('tpl',$scriptProperties,'option');
$selected = $modx->getOption('selected',$scriptProperties,'');
$useAbbr = $modx->getOption('useAbbr',$scriptProperties,true);
$selectedAttribute = $modx->getOption('selectedAttribute',$scriptProperties,' selected="selected"');

$selectedKey = $useAbbr ? 'stateKey' : 'stateName';
$output = '';
$countries = include $fi->config['includesPath'].'us.states.inc.php';
foreach ($countries as $stateKey => $stateName) {
    $stateArray = array(
        'text' => $stateName,
        'value' => $stateKey,
        'selected' => '',
    );
    if ($selected == $$selectedKey) {
        $stateArray['selected'] = $selectedAttribute;
    }
    $output[] = $fi->getChunk($tpl,$stateArray);
}

$output = implode("\n",$output);
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,'');
if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder,$output);
    $output = '';
}
return $output;