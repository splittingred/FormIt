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
 * A custom hook for auto-responders.
 * 
 * @package formit
 */
/* setup default properties */
$tpl = $modx->getOption('fiarTpl',$scriptProperties,'fiarTpl');
$mailFrom = $modx->getOption('fiarFrom',$scriptProperties,$modx->getOption('emailsender'));
$mailFromName = $modx->getOption('fiarFromName',$scriptProperties,$modx->getOption('site_name'));
$mailSender = $modx->getOption('fiarSender',$scriptProperties,$modx->getOption('emailsender'));
$mailSubject = $modx->getOption('fiarSubject',$scriptProperties,'[[++site_name]] Auto-Responder');
$mailSubject = str_replace(array('[[++site_name]]','[[++emailsender]]'),array($modx->getOption('site_name'),$modx->getOption('emailsender')),$mailSubject);
$mailReplyTo = $modx->getOption('fiarReplyTo',$scriptProperties,$mailFrom);
$isHtml = $modx->getOption('fiarHtml',$scriptProperties,true);
$toField = $modx->getOption('fiarToField',$scriptProperties,'email');
if (empty($scriptProperties['fields'][$toField])) {
    $modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] Auto-responder could not find field `'.$toField.'` in form submission.');
    return false;
}

/* setup placeholders */
$placeholders = $scriptProperties['fields'];
$mailTo= $scriptProperties['fields'][$toField];

$message = $scriptProperties['formit']->getChunk($tpl,$placeholders);

$modx->getService('mail', 'mail.modPHPMailer');
$modx->mail->set(modMail::MAIL_BODY,$message);
$modx->mail->set(modMail::MAIL_FROM,$scriptProperties['hook']->_process($mailFrom,$placeholders));
$modx->mail->set(modMail::MAIL_FROM_NAME,$scriptProperties['hook']->_process($mailFromName,$placeholders));
$modx->mail->set(modMail::MAIL_SENDER,$scriptProperties['hook']->_process($mailSender,$placeholders));
$modx->mail->set(modMail::MAIL_SUBJECT,$scriptProperties['hook']->_process($mailSubject,$placeholders));
$modx->mail->address('to',$mailTo);
$modx->mail->setHTML($isHtml);

/* reply to */
$emailReplyTo = $modx->getOption('fiarReplyTo',$scriptProperties,$emailFrom);
$emailReplyTo = $scriptProperties['hook']->_process($emailReplyTo,$fields);
$emailReplyToName = $modx->getOption('fiarReplyToName',$scriptProperties,$emailFromName);
$emailReplyToName = $scriptProperties['hook']->_process($emailReplyToName,$fields);
$modx->mail->address('reply-to',$emailReplyTo,$emailReplyToName);

/* cc */
$emailCC = $modx->getOption('fiarCC',$scriptProperties,'');
if (!empty($emailCC)) {
    $emailCCName = $modx->getOption('fiarCCName',$scriptProperties,'');
    $emailCC = explode(',',$emailCC);
    $emailCCName = explode(',',$emailCCName);
    $numAddresses = count($emailCC);
    for ($i=0;$i<$numAddresses;$i++) {
        $etn = !empty($emailCCName[$i]) ? $emailCCName[$i] : '';
        if (!empty($etn)) $etn = $scriptProperties['hook']->_process($etn,$fields);
        $emailCC[$i] = $scriptProperties['hook']->_process($emailCC[$i],$fields);
        $modx->mail->address('cc',$emailCC[$i],$etn);
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
        if (!empty($etn)) $etn = $scriptProperties['hook']->_process($etn,$fields);
        $emailBCC[$i] = $scriptProperties['hook']->_process($emailBCC[$i],$fields);
        $modx->mail->address('bcc',$emailBCC[$i],$etn);
    }
}

if (!$modx->mail->send()) {
    $modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] An error occurred while trying to send the auto-responder email: '.$modx->mail->mailer->ErrorInfo);
    return false;
}
$modx->mail->reset();
return true;