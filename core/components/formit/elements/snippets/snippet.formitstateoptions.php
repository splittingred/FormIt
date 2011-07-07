<?php
/**
 * FormIt
 *
 * Copyright 2009-2011 by Shaun McCormick <shaun@modx.com>
 *
 * FormIt is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * FormIt is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * FormIt; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package formit
 */
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

$outputSeparator = $modx->getOption('outputSeparator',$scriptProperties,"\n");
$output = implode($outputSeparator,$output);
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,'');
if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder,$output);
    $output = '';
}
return $output;