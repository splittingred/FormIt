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
$updateSavedForm = $modx->getOption('updateSavedForm', $formit->config, false); // true, false, '1', '0', or 'values'
// In order to use update process, you need to provide the hash key to the user somehow
// Usually with [[!FormItRetriever]] to populate this form field:
$formHashKeyField = $modx->getOption('savedFormHashKeyField', $formit->config, 'savedFormHashKey');
// Disable if you want to use the session_id() in your hash, like FormIt does
// WARNING: this can cause potential hash key collisions and overwriting of the wrong form record!!
$formHashKeyRandom = $modx->getOption('formHashKeyRandom', $formit->config, true);
// process formHashKeyField into variable for later use
$formHashKey = (isset($values[$formHashKeyField])) ? (string) $values[$formHashKeyField] : '';
// our hashing methods return 32 chars
if (strlen($formHashKey) !== 32) $formHashKey = '';
unset($values[$formHashKeyField]);

if ($formFields) {
    $formFields = explode(',', $formFields);
    foreach($formFields as $k => $v) {
        $formFields[$k] = trim($v);
    }
}
// Build the data array
$dataArray = array();
if ($formFields) {
    foreach ($formFields as $field) {
        $fieldValue = isset($values[$field]) ? $values[$field] : '';
        // When field is file field, value is an array
        if (is_array($fieldValue) && isset($fieldValue['tmp_name'], $fieldValue['name'])) {
            $fieldValue = $fieldValue['name'];
        }
        $dataArray[$field] = $fieldValue;
    }
} else {
    $dataArray = $values;
}
// Change the fieldnames
if ($fieldNames){
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
// We only enter update mode if we already have a valid formHashKey (tested above)
// AND the updateSavedForm param was set to a truth-y value.
$mode = ($updateSavedForm && $formHashKey) ? 'update' : 'create';
// Create/get obj
$newForm = null;
if ($mode === 'update') {
    $newForm = $modx->getObject('FormItForm', array('hash' => $formHashKey));
}
if ($newForm === null) $newForm = $modx->newObject('FormItForm');

// Handle encryption
if($formEncrypt){
    $dataArray = $newForm->encrypt($modx->toJSON($dataArray));
} else {
    $dataArray = $modx->toJSON($dataArray);
}

// Create new hash key on create mode, and handle invalid hash keys. 
if ($mode === 'create') {
    $formHashKey = ($formHashKeyRandom) ? $newForm->generatePseudoRandomHash() : pathinfo($formit->getStoreKey(), PATHINFO_BASENAME);
}

// Array from which to populate form record
$newFormArray = array();

// Special case: if updateSavedForm has the flag 'values' we only merge in
// the form values, not the other stuff
if ($mode === 'update' && $updateSavedForm === 'values') {
    $newFormArray = $newForm->toArray();
    $newFormArray = array_merge($newFormArray, array(
        'values' => $dataArray,
    ));       
} else {
    // In all other cases, we overwrite the record completely!
    // In create mode we must save the hash. In update mode, the 
    // formHashKey will be valid so we can also save it, again.
    $newFormArray = array(
        'form' => $formName,
        'date' => time(),
        'values' => $dataArray,
        'ip' => $modx->getOption('REMOTE_ADDR', $_SERVER, ''),
        'context_key' => $modx->resource->get('context_key'),
        'encrypted' => $formEncrypt,
        'hash' => $formHashKey,
    );
}
// Convert to object
$newForm->fromArray($newFormArray);
// Attempt to save
if (!$newForm->save()) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[FormItSaveForm] An error occurred while trying to save the submitted form: ' . print_r($newForm->toArray(), true));
    return false;
}
// Pass the hash and form data back to the user
$hook->setValue('savedForm', $newForm->toArray());
$hook->setValue($formHashKeyField, $newForm->get('hash'));
return true;