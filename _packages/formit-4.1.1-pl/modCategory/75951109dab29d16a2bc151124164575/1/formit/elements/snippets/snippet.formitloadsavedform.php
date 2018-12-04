<?php
/**
 * FormIt
 *
 * Copyright 2011-12 by SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 * Copyright 2015 by Wieger Sloot <modx@sterc.nl>
 * Copyright 2016 by YJ Tso <yj@modx.com>
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
 * A custom FormIt prehook for fetching saved form data. - Based on FormItSaveForm
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var FormIt $formit
 * @var fiHooks $hook
 * 
 * @package formit
 */
/* setup default properties */
// If prehook fails do we continue?
$return = $modx->getOption('returnValueOnFail', $formit->config, true);
$formEncrypt = $modx->getOption('formEncrypt', $formit->config, false);
$formFields = $modx->getOption('formFields', $formit->config, false);
$fieldNames = $modx->getOption('fieldNames', $formit->config, false);
$updateSavedForm = $modx->getOption('updateSavedForm', $formit->config, false); // true, false, '1', '0', or 'values'
// If FormIt config says don't update, don't do it
if (!$updateSavedForm) return $return;
// In order to load form values, the user must provide the hash key somehow
// Usually with a $_GET parameter, but a property in $formit->config will override.
$formHashKeyField = $modx->getOption('savedFormHashKeyField', $formit->config, 'savedFormHashKey');
$formHashKey = '';
if (isset($_GET[$formHashKeyField])) $formHashKey = (string) $_GET[$formHashKeyField];
if ($hook->getValue($formHashKeyField)) $formHashKey = (string) $hook->getValue($formHashKeyField);
if (isset($formit->config[$formHashKeyField])) $formHashKey = $formit->config[$formHashKeyField];
// our hashing methods return 32 chars. if no valid hash key we're done here.
if (strlen($formHashKey) !== 32) return $return;

// Try to fetch the saved form
$savedForm = $modx->getObject('FormItForm', array('hash' => $formHashKey));
if (!$savedForm) return $return;

if ($formFields) {
    $formFields = explode(',', $formFields);
    foreach($formFields as $k => $v) {
        $formFields[$k] = trim($v);
    }
}

// Initialize the data array
// Handle encryption
if ($formEncrypt) {
    $data = $savedForm->decrypt();
} else {
    $data = $savedForm->get('values');
}
if (is_string($data)) $data = $modx->fromJSON($data);
if (!is_array($data)) return $return;

//Change the fieldnames
if ($fieldNames) {
    $newDataArray = array();
    $fieldLabels = array();
    $formFieldNames = explode(',', $fieldNames);
    foreach($formFieldNames as $formFieldName){
        list($name, $label) = explode('==', $formFieldName);
        // reverse order from FormItSaveForm snippet
        $fieldLabels[trim($label)] = trim($name);
    }
    foreach ($data as $key => $value) {
        if ($fieldLabels[$key]) {
            $newDataArray[$fieldLabels[$key]] = $value;
        }else{
            $newDataArray[$key] = $value;
        }
    }
    $data = $newDataArray;
}

// Always pass back in the provided hash key
$data[$formHashKeyField] = $formHashKey;
$hook->setValues($data);
return true;