<?php
/**
 * FormIt
 *
 * Copyright 2011-12 by SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 * Copyright 2015 by Wieger Sloot <modx@sterc.nl>
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
 * A custom FormIt hook for saving filled-in forms. - Based on FormSave
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var FormIt $formit
 * @var fiHooks $hook
 * 
 * @package formit
 */
/* setup default properties */
$values = $hook->getValues();
$formName = $modx->getOption('formName', $formit->config, 'form-'.$modx->resource->get('id'));
// process formName. Pick a value from the form
// Inspired from the email's hook of formit (fihooks.class.php)
if (is_string($formName)) {
    foreach ($fields as $k => $v) {
        if (is_scalar($k) && is_scalar($v)) {
            $formName = str_replace('[[+'.$k.']]',$v,$formName);
        }
    }
}

$formEncrypt = $modx->getOption('formEncrypt', $formit->config, false);
$formFields = $modx->getOption('formFields', $formit->config, false);
$fieldNames = $modx->getOption('fieldNames', $formit->config, false);
if ($formFields) {
    $formFields = explode(',', $formFields);
    foreach($formFields as $k => $v) {
        $formFields[$k] = trim($v);
    }
}
// Build the data array
$dataArray = array();
if($formFields){
    foreach($formFields as $field) {
        $dataArray[$field] = (!isset($values[$field])) ? '' : $values[$field];
    }
}else{
    $dataArray = $values;
}
//Change the fieldnames
if($fieldNames){
    $newDataArray = array();
    $fieldLabels = array();
    $formFieldNames = explode(',', $fieldNames);
    foreach($formFieldNames as $formFieldName){
        list($name, $label) = explode('==', $formFieldName);
        $fieldLabels[trim($name)] = trim($label);
    }
    foreach ($dataArray as $key => $value) {
        if($fieldLabels[$key]){
            $newDataArray[$fieldLabels[$key]] = $value;
        }else{
            $newDataArray[$key] = $value;
        }
    }
    $dataArray = $newDataArray;
}
// Create obj
$newForm = $modx->newObject('FormItForm');
if($formEncrypt){
    $dataArray = $newForm->encrypt($modx->toJSON($dataArray));
}else{
    $dataArray = $modx->toJSON($dataArray);
}
$newForm->fromArray(array(
    'form' => $formName,
    'date' => time(),
    'values' => $dataArray,
    'ip' => $modx->getOption('REMOTE_ADDR', $_SERVER, ''),
    'context_key' => $modx->resource->get('context_key'),
    'encrypted' => $formEncrypt
));

if (!$newForm->save()) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[FormItSaveForm] An error occurred while trying to save the submitted form: ' . print_r($newForm->toArray(), true));
    return false;
}
$hook->setValue('savedForm', $newForm->toArray());
return true;
