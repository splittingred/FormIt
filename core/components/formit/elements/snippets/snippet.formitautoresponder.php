<?php
/**
 * FormIt
 *
 * Copyright 2009-2012 by Shaun McCormick <shaun@modx.com>
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
 * A custom FormIt hook for auto-responders.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var FormIt $formit
 * @var fiHooks $hook
 * 
 * @package formit
 */
/* setup default properties */
$tpl = $modx->getOption('fiarTpl',$scriptProperties,'fiDefaultFiarTpl');
$mailFrom = $modx->getOption('fiarFrom',$scriptProperties,$modx->getOption('emailsender'));
$mailFromName = $modx->getOption('fiarFromName',$scriptProperties,$modx->getOption('site_name'));
$mailSender = $modx->getOption('fiarSender',$scriptProperties,$modx->getOption('emailsender'));
$mailSubject = $modx->getOption('fiarSubject',$scriptProperties,'[[++site_name]] Auto-Responder');
$mailSubject = str_replace(array('[[++site_name]]','[[++emailsender]]'),array($modx->getOption('site_name'),$modx->getOption('emailsender')),$mailSubject);
$fiarFiles = $modx->getOption('fiarFiles',$scriptProperties,false);
$isHtml = $modx->getOption('fiarHtml',$scriptProperties,true);
$toField = $modx->getOption('fiarToField',$scriptProperties,'email');
$multiSeparator = $modx->getOption('fiarMultiSeparator',$formit->config,"\n");
$multiWrapper = $modx->getOption('fiarMultiWrapper',$formit->config,"[[+value]]");
$required = $modx->getOption('fiarRequired',$scriptProperties,true);
if (empty($fields[$toField])) {
    if ($required) {
        $modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] Auto-responder could not find field `'.$toField.'` in form submission.');
        return false;
    } else {
        return true;
    }
}

/* handle checkbox and array fields */
foreach ($fields as $k => &$v) {
    if (is_array($v) && !empty($v['name']) && isset($v['error']) && $v['error'] == UPLOAD_ERR_OK) {
        $fields[$k] = $v['name'];
    } else if (is_array($v)) {
        $vOpts = array();
        foreach ($v as $vKey => $vValue) {
            if (is_string($vKey) && !empty($vKey)) {
                $vKey = $k.'.'.$vKey;
                $fields[$vKey] = $vValue;
            } else {
                $vOpts[] = str_replace('[[+value]]',$vValue,$multiWrapper);
            }
        }
        $newValue = implode($multiSeparator,$vOpts);
        if (!empty($vOpts)) {
            $fields[$k] = $newValue;
        }
    }
}

/* setup placeholders */
$placeholders = $fields;
$mailTo= $fields[$toField];

$message = $formit->getChunk($tpl,$placeholders);
$modx->parser->processElementTags('',$message,true,false);

$modx->getService('mail', 'mail.modPHPMailer');
$modx->mail->reset();
$modx->mail->set(modMail::MAIL_BODY,$message);
$modx->mail->set(modMail::MAIL_FROM,$hook->_process($mailFrom,$placeholders));
$modx->mail->set(modMail::MAIL_FROM_NAME,$hook->_process($mailFromName,$placeholders));
$modx->mail->set(modMail::MAIL_SENDER,$hook->_process($mailSender,$placeholders));
$modx->mail->set(modMail::MAIL_SUBJECT,$hook->_process($mailSubject,$placeholders));
$modx->mail->address('to',$mailTo);
$modx->mail->setHTML($isHtml);

/* add attachments */
if($fiarFiles){
    $fiarFiles = explode(',', $fiarFiles);
    foreach($fiarFiles AS $file){
        $modx->mail->mailer->AddAttachment($file);
    }
}

/* reply to */
$emailReplyTo = $modx->getOption('fiarReplyTo',$scriptProperties,$mailFrom);
$emailReplyTo = $hook->_process($emailReplyTo,$fields);
$emailReplyToName = $modx->getOption('fiarReplyToName',$scriptProperties,$mailFromName);
$emailReplyToName = $hook->_process($emailReplyToName,$fields);
if (!empty($emailReplyTo)) {
    $modx->mail->address('reply-to',$emailReplyTo,$emailReplyToName);
}

/* cc */
$emailCC = $modx->getOption('fiarCC',$scriptProperties,'');
if (!empty($emailCC)) {
    $emailCCName = $modx->getOption('fiarCCName',$scriptProperties,'');
    $emailCC = explode(',',$emailCC);
    $emailCCName = explode(',',$emailCCName);
    $numAddresses = count($emailCC);
    for ($i=0;$i<$numAddresses;$i++) {
        $etn = !empty($emailCCName[$i]) ? $emailCCName[$i] : '';
        if (!empty($etn)) $etn = $hook->_process($etn,$fields);
        $emailCC[$i] = $hook->_process($emailCC[$i],$fields);
        if (!empty($emailCC[$i])) {
            $modx->mail->address('cc',$emailCC[$i],$etn);
        }
    }
}

/* bcc */
$emailBCC = $modx->getOption('fiarBCC',$scriptProperties,'');
if (!empty($emailBCC)) {
    $emailBCCName = $modx->getOption('fiarBCCName',$scriptProperties,'');
    $emailBCC = explode(',',$emailBCC);
    $emailBCCName = explode(',',$emailBCCName);
    $numAddresses = count($emailBCC);
    for ($i=0;$i<$numAddresses;$i++) {
        $etn = !empty($emailBCCName[$i]) ? $emailBCCName[$i] : '';
        if (!empty($etn)) $etn = $hook->_process($etn,$fields);
        $emailBCC[$i] = $hook->_process($emailBCC[$i],$fields);
        if (!empty($emailBCC[$i])) {
            $modx->mail->address('bcc',$emailBCC[$i],$etn);
        }
    }
}

if (!$formit->inTestMode) {
    if (!$modx->mail->send()) {
        $modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] An error occurred while trying to send the auto-responder email: '.$modx->mail->mailer->ErrorInfo);
        return false;
    }
}
$modx->mail->reset();
return true;