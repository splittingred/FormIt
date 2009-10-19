<?php
/**
 * FormIt
 *
 * Copyright 2009 by Shaun McCormick <shaun@collabpad.com>
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
 * Base Hooks handling class
 *
 * @package formit
 */
class fiHooks {
    /**
     * @var array $errors A collection of all the processed errors so far.
     * @access public
     */
    public $errors = array();
    /**
     * @var array $hooks A collection of all the processed hooks so far.
     * @access public
     */
    public $hooks = array();
    /**
     * @var modX $modx A reference to the modX instance.
     * @access public
     */
    public $modx = null;
    /**
     * @var FormIt $formit A reference to the FormIt instance.
     * @access public
     */
    public $formit = null;

    /**
     * The constructor for the fiHooks class
     *
     * @param FormIt &$formit A reference to the FormIt class instance.
     * @param array $config Optional. An array of configuration parameters.
     * @return fiHooks
     */
    function __construct(FormIt &$formit,array $config = array()) {
        $this->formit =& $formit;
        $this->modx =& $formit->modx;
        $this->config = array_merge(array(
        ),$config);
    }

    /**
     * Loads an array of hooks. If one fails, will not proceed.
     *
     * @access public
     * @param array $hooks The hooks to run.
     * @parma array $fields The fields and values of the form
     * @return array An array of field name => value pairs.
     */
    public function loadMultiple($hooks,$fields) {
        if (empty($hooks)) return array();
        if (is_string($hooks)) $hooks = explode(',',$hooks);

        $this->hooks = array();
        foreach ($hooks as $hook) {
            $success = $this->load($hook,$fields);
            if (!$success) return $this->hooks;
            /* dont proceed if hook fails */
        }
        return $this->hooks;
    }

    /**
     * Load a hook. Stores any errors for the hook to $this->errors.
     *
     * @access public
     * @param string $hook The name of the hook. May be a Snippet name.
     * @param array $fields The fields and values of the form.
     * @return boolean True if hook was successful.
     */
    public function load($hook,$fields = array()) {
        $success = false;
        $this->hooks[] = $hook;

        if (method_exists($this,$hook) && $hook != 'load') {
            /* built-in hooks */
            $success = $this->$hook($fields);

        } else if ($snippet = $this->modx->getObject('modSnippet',array('name' => $hook))) {
            /* custom snippet hook */
            $properties = $this->formit->config;
            $properties['hook'] =& $this;
            $properties['fields'] = $fields;
            $success = $snippet->process($properties);

        } else {
            /* no hook found */
            $this->modx->log(MODX_LOG_LEVEL_ERROR,'[FormIt] Could not find hook "'.$hook.'".');
            $success = true;
        }

        if (is_array($success) && !empty($success)) {
            $this->errors = array_merge($this->errors,$success);
            $success = false;
        } else if ($success !== true) {
            $this->errors[$hook] .= ' '.$success;
            $success = false;
        }
        return $success;
    }

    /**
     * Gets the error messages compiled into a single string.
     *
     * @access public
     * @param string $delim The delimiter between each message.
     * @return string The concatenated error message
     */
    public function getErrorMessage($delim = "\n") {
        return implode($delim,$this->errors);
    }

    /**
     * Redirect to a specified URL.
     *
     * Properties needed:
     * - redirectTo - the ID of the Resource to redirect to.
     *
     * @param array $fields An array of cleaned POST fields
     * @return boolean False if unsuccessful.
     */
    public function redirect($fields = array()) {
        if (empty($this->formit->config['redirectTo'])) return false;

        $url = $this->modx->makeUrl($this->formit->config['redirectTo']);
        return $this->modx->sendRedirect($url);
    }

    /**
     * Send an email of the form.
     *
     * Properties:
     * - emailTpl - The chunk name of the chunk that will be the email template.
     * This will send the values of the form as placeholders.
     * - emailTo - A comma separated list of email addresses to send to
     * - emailToName - A comma separated list of names to pair with addresses.
     * - emailFrom - The From: email address. Defaults to either the email
     * field or the emailsender setting.
     * - emailFromName - The name of the From: user.
     * - emailSubject - The subject of the email.
     * - emailHtml - Boolean, if true, email will be in HTML mode.
     *
     * @access public
     * @param array $fields An array of cleaned POST fields
     * @return boolean True if email was successfully sent.
     */
    public function email($fields = array()) {
        $tpl = $this->modx->getOption('emailTpl',$this->formit->config,'');
        if (empty($tpl)) {
            $this->errors[] = $this->modx->lexicon('formit.email_tpl_nf');
            return false;
        }

        $emailFrom = empty($this->fields['email']) ? $this->modx->getOption('emailsender') : $this->fields['email'];
        $emailFrom = $this->modx->getOption('emailFrom',$this->formit->config,$emailFrom);
        $emailFromName = $this->modx->getOption('emailFromName',$this->formit->config,$emailFrom);
        $emailHtml = $this->modx->getOption('emailHtml',$this->formit->config,true);
        $subject = $this->modx->getOption('emailSubject',$this->formit->config,'');

        /* check email to */
        $emailTo = $this->modx->getOption('emailTo',$this->formit->config,'');
        $emailToName = $this->modx->getOption('emailToName',$this->formit->config,$emailTo);
        if (empty($emailTo)) {
            $this->errors[] = $this->modx->lexicon('formit.email_no_recipient');
            return false;
        }

        /* compile message */
        $message = $this->modx->getChunk($tpl,$fields);

        /* load mail service */
        $this->modx->getService('mail', 'mail.modPHPMailer');
        $this->modx->mail->set(MODX_MAIL_BODY, $message);
        $this->modx->mail->set(MODX_MAIL_FROM, $emailFrom);
        $this->modx->mail->set(MODX_MAIL_FROM_NAME, $emailFromName);
        $this->modx->mail->set(MODX_MAIL_SENDER, $emailFrom);
        $this->modx->mail->set(MODX_MAIL_SUBJECT, $subject);

        /* add to: with support for multiple addresses */
        $emailTo = explode(',',$emailTo);
        $emailToName = explode(',',$emailToName);
        $numAddresses = count($emailTo);
        for ($i=0;$i<$numAddresses;$i++) {
            $etn = !empty($emailToName[$i]) ? $emailToName[$i] : '';
            $this->modx->mail->address('to',$emailTo[$i],$etn);
        }
        $this->modx->mail->address('reply-to',$emailFrom);
        $this->modx->mail->setHTML($emailHtml);

        /* send email */
        $sent = $this->modx->mail->send();
        $this->modx->mail->reset();

        if (!$sent) {
            $this->errors[] = $this->modx->lexicon('formit.email_not_sent');
        }

        return $sent;
    }

}