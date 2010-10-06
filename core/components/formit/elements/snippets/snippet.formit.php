<?php
/**
 * FormIt
 *
 * Copyright 2009-2010 by Shaun McCormick <shaun@modx.com>
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
 * FormIt
 *
 * A dynamic form processing Snippet for MODx Revolution 2.0.
 *
 * @package formit
 */
/* load FormIt classes */
require_once $modx->getOption('formit.core_path',null,$modx->getOption('core_path').'components/formit/').'model/formit/formit.class.php';
$fi = new FormIt($modx,$scriptProperties);
$fi->initialize($modx->context->get('key'));

/* get default properties */
$submitVar = $modx->getOption('submitVar',$scriptProperties,false);
$hooks = $modx->getOption('hooks',$scriptProperties,'');
$preHooks = $modx->getOption('preHooks',$scriptProperties,'');
$errTpl = $modx->getOption('errTpl',$scriptProperties,'<span class="error">[[+error]]</span>');
$store = $modx->getOption('store',$scriptProperties,false);
$fieldsToValidate = $modx->getOption('fieldsToValidate',$scriptProperties,false);

/* if using recaptcha, load recaptcha html */
if (strpos($hooks,'recaptcha') !== false) {
    $recaptcha = $modx->getService('recaptcha','FormItReCaptcha',$fi->config['modelPath'].'recaptcha/');
    if ($recaptcha instanceof FormItReCaptcha) {
        $recaptchaTheme = $modx->getOption('recaptchaTheme',$scriptProperties,'clean');
        $recaptchaWidth = $modx->getOption('recaptchaWidth',$scriptProperties,500);
        $recaptchaHeight = $modx->getOption('recaptchaHeight',$scriptProperties,300);
        $html = $recaptcha->getHtml($recaptchaTheme,$recaptchaWidth,$recaptchaHeight);
        $modx->setPlaceholder('formit.recaptcha_html',$html);
    } else {
        $modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] '.$modx->lexicon('formit.recaptcha_err_load'));
    }
}

/* load preHooks */
$fi->loadHooks('pre');
$fi->preHooks->loadMultiple($preHooks,array(),array(
    'submitVar' => $submitVar,
    'hooks' => $hooks,
));
/* if a prehook sets a field, do so here */
if (!empty($fi->preHooks->fields) && empty($_POST)) {
    $modx->toPlaceholders($fi->preHooks->fields,'fi');
    $fields = $fi->preHooks->fields;
}
/* if any errors in preHooks */
if (!empty($fi->preHooks->errors)) {
    $errors = array();
    foreach ($fi->preHooks->errors as $key => $error) {
        $errors[$key] = str_replace('[[+error]]',$error,$errTpl);
    }
    $modx->toPlaceholders($errors,'fi.error');

    $errorMsg = $fi->preHooks->getErrorMessage();
    if (!empty($errorMsg)) {
        $modx->toPlaceholder('error_message',$errorMsg,'fi.error');
    }
}

/* make sure appropriate POST occurred */
if (empty($_POST)) return '';
if (!empty($submitVar) && empty($_POST[$submitVar])) return '';

/* validate fields */
$fi->loadValidator();
if (empty($fields)) $fields = array();
$fields = array_merge($fields,$_POST);
if (!empty($_FILES)) { $fields = array_merge($fields,$_FILES); }
if ($fieldsToValidate) {
  $fieldsToValidate = explode(',',$fieldsToValidate);
  foreach ($fieldsToValidate as $key) {
    if (!array_key_exists($key, $fields)) {
      $fields[$key] = '';
    }
  }
}
$fields = $fi->validator->validateFields($fields);

if (empty($fi->validator->errors)) {
    /* if set, store fields */
    if (!empty($store)) {
         /* default to store data for 5 minutes */
        $storeTime = $modx->getOption('storeTime',$scriptProperties,300);
        /* create the hash to store */
        $cacheKey = $fi->getStoreKey();
        $modx->cacheManager->set($cacheKey,$fields,$storeTime);
    }

    /* load posthooks */
    $fi->loadHooks('post');
    $fi->postHooks->loadMultiple($hooks,$fields);

    /* process form */
    if (!empty($fi->postHooks->errors)) {
        $errors = array();
        foreach ($fi->postHooks->errors as $key => $error) {
            $errors[$key] = str_replace('[[+error]]',$error,$errTpl);
        }
        $modx->toPlaceholders($errors,'fi.error');

        $errorMsg = $fi->postHooks->getErrorMessage();
        $modx->toPlaceholder('error_message',$errorMsg,'fi.error');
    } else {
        /* set success placeholder */
        $modx->toPlaceholder('success',true,'fi');
        $successMsg = $modx->getOption('successMessage',$scriptProperties,'');
        if (!empty($successMsg)) {
            $smPlaceholder = $modx->getOption('successMessagePlaceholder',$scriptProperties,'fi.successMessage');
            $modx->setPlaceholder($smPlaceholder,$successMsg);
        }
        /* if clearing fields on success, just end here */
        if ($modx->getOption('clearFieldsOnSuccess',$scriptProperties,true)) {
            return '';
        }
    }

} else {
    $modx->toPlaceholders($fi->validator->errors,'fi.error');
}
$modx->toPlaceholders($fields,'fi');

return '';