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
 * Automatically generates and outputs a country list for usage in forms
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @package formit
 */
require_once $modx->getOption('formit.core_path',null,$modx->getOption('core_path').'components/formit/').'model/formit/formit.class.php';
$fi = new FormIt($modx,$scriptProperties);

/* Collect default properties */
$tpl = $modx->getOption('tpl',$scriptProperties,'option');
$selected = $modx->getOption('selected',$scriptProperties,'');
$useIsoCode = $modx->getOption('useIsoCode',$scriptProperties,true);
$selectedAttribute = $modx->getOption('selectedAttribute',$scriptProperties,' selected="selected"');
$optGroupTpl = $modx->getOption('optGroupTpl',$scriptProperties,'optGroup');
$prioritized = $modx->getOption('prioritized',$scriptProperties,'');
$prioritizedGroupText = $modx->getOption('prioritizedGroupText',$scriptProperties,'');
$allGroupText = $modx->getOption('allGroupText',$scriptProperties,'');

/* get Countries and the proper selected key */
$selectedKey = $useIsoCode ? 'countryKey' : 'countryName';
$output = '';
$countries = include $fi->config['includesPath'].'countries.inc.php';

/* handle prioritized countries */
$prioritizedCountries = array();
if (!empty($prioritized)) {
    $prioritized = explode(',',$prioritized);
    foreach ($countries as $countryKey => $countryName) {
        if (in_array($countryKey,$prioritized)) {
            $prioritizedCountries[] = $countryKey;
        }
    }
}

/* iterate over lists */
$list = array();
$prioritizedList = array();
foreach ($countries as $countryKey => $countryName) {
    $countryArray = array(
        'text' => $countryName,
        'value' => $useIsoCode ? $countryKey : $countryName,
        'selected' => '',
    );
    if ($selected == $$selectedKey) {
        $countryArray['selected'] = $selectedAttribute;
    }
    $o = $fi->getChunk($tpl,$countryArray);
    if (in_array($countryKey,$prioritizedCountries)) {
        $prioritizedList[] = $o;
    } else {
        $list[] = $o;
    }
}

/* handle output generation */
$outputSeparator = $modx->getOption('outputSeparator',$scriptProperties,"\n");
if (!empty($prioritizedList)) {
    $output = $fi->getChunk($optGroupTpl,array(
        'text' => !empty($prioritizedGroupText) ? $prioritizedGroupText : $modx->lexicon('formit.prioritized_group_text'),
        'options' => implode($outputSeparator,$prioritizedList),
    ));
    $output .= $fi->getChunk($optGroupTpl,array(
        'text' => !empty($allGroupText) ? $allGroupText : $modx->lexicon('formit.all_group_text'),
        'options' => implode($outputSeparator,$list),
    ));
} else {
    $output = implode($outputSeparator,$list);
}

/* set to placeholder or output */
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,'');
if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder,$output);
    $output = '';
}
return $output;