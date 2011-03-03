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
     * @var string If a hook redirects, it needs to set this var to use proper
     * order of execution on redirects/stores
     * @access public
     */
    public $redirectUrl = null;

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
     * @param array $fields The fields and values of the form
     * @param array $customProperties An array of extra properties to send to the hook
     * @return array An array of field name => value pairs.
     */
    public function loadMultiple($hooks,array $fields = array(),array $customProperties = array()) {
        if (empty($hooks)) return array();
        if (is_string($hooks)) $hooks = explode(',',$hooks);

        $this->hooks = array();
        $this->fields =& $fields;
        foreach ($hooks as $hook) {
            $hook = trim($hook);
            $success = $this->load($hook,$this->fields,$customProperties);
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
     * @param array $customProperties Any other custom properties to load into a custom hook.
     * @return boolean True if hook was successful.
     */
    public function load($hook,array $fields = array(),array $customProperties = array()) {
        $success = false;
        if (!empty($fields)) $this->fields =& $fields;
        $this->hooks[] = $hook;

        $reserved = array('load','_process','__construct','getErrorMessage');
        if (method_exists($this,$hook) && !in_array($hook,$reserved)) {
            /* built-in hooks */
            $success = $this->$hook($this->fields);

        } else if ($snippet = $this->modx->getObject('modSnippet',array('name' => $hook))) {
            /* custom snippet hook */
            $properties = array_merge($this->formit->config,$customProperties);
            $properties['formit'] =& $this->formit;
            $properties['hook'] =& $this;
            $properties['fields'] = $this->fields;
            $properties['errors'] =& $this->errors;
            $success = $snippet->process($properties);

        } else {
            /* no hook found */
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] Could not find hook "'.$hook.'".');
            $success = true;
        }

        if (is_array($success) && !empty($success)) {
            $this->errors = array_merge($this->errors,$success);
            $success = false;
        } else if ($success != true) {
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
     * Adds an error to the stack.
     *
     * @access private
     * @param string $key The field to add the error to.
     * @param string $value The error message.
     * @return string The added error message with the error wrapper.
     */
    public function addError($key,$value) {
        $this->errors[$key] .= $value;
        return $this->errors[$key];
    }

    /**
     * Sets the value of a field.
     *
     * @param string $key The field name to set.
     * @param mixed $value The value to set to the field.
     * @return mixed The set value.
     */
    public function setValue($key,$value) {
        $this->fields[$key] = $value;
        return $this->fields[$key];
    }

    /**
     * Sets an associative array of field name and values.
     *
     * @param array $values A key/name pair of fields and values to set.
     */
    public function setValues(array $values = array()) {
        foreach ($values as $key => $value) {
            $this->setValue($key,$value);
        }
    }

    /**
     * Gets the value of a field.
     *
     * @param string $key The field name to get.
     * @return mixed The value of the key, or null if non-existent.
     */
    public function getValue($key) {
        if (array_key_exists($key,$this->fields)) {
            return $this->fields[$key];
        }
        return null;
    }

    /**
     * Gets an associative array of field name and values.
     *
     * @return array $values A key/name pair of fields and values.
     */
    public function getValues() {
        return $this->fields;
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
    public function redirect(array $fields = array()) {
        if (empty($this->formit->config['redirectTo'])) return false;
        $redirectParams = !empty($this->formit->config['redirectParams']) ? $this->formit->config['redirectParams'] : '';
        if (!empty($redirectParams)) {
            $prefix = $this->modx->getOption('placeholderPrefix',$this->formit->config,'fi.');
            $this->modx->setPlaceholders($fields,$prefix);
            $this->modx->parser->processElementTags('',$redirectParams,true,true);
            $redirectParams = $this->modx->fromJSON($redirectParams);
            if (empty($redirectParams)) $redirectParams = '';
        }
        $url = $this->modx->makeUrl($this->formit->config['redirectTo'],'',$redirectParams,'abs');
        $this->setRedirectUrl($url);
        return true;
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
    public function email(array $fields = array()) {
        $tpl = $this->modx->getOption('emailTpl',$this->formit->config,'');
        $emailHtml = $this->modx->getOption('emailHtml',$this->formit->config,true);

        /* get from name */
        $emailFrom = $this->modx->getOption('emailFrom',$this->formit->config,'');
        if (empty($emailFrom)) {
            $emailFrom = !empty($fields['email']) ? $fields['email'] : $this->modx->getOption('emailsender');
        }
        $emailFrom = $this->_process($emailFrom,$fields);
        $emailFromName = $this->modx->getOption('emailFromName',$this->formit->config,$emailFrom);
        $emailFromName = $this->_process($emailFromName,$fields);

        /* get subject */
        $useEmailFieldForSubject = $this->modx->getOption('emailUseFieldForSubject',$this->formit->config,true);
        if (!empty($fields['subject']) && $useEmailFieldForSubject) {
            $subject = $fields['subject'];
        } else {
            $subject = $this->modx->getOption('emailSubject',$this->formit->config,'');
        }
        $subject = $this->_process($subject,$fields);

        /* check email to */
        $emailTo = $this->modx->getOption('emailTo',$this->formit->config,'');
        $emailToName = $this->modx->getOption('emailToName',$this->formit->config,$emailTo);
        if (empty($emailTo)) {
            $this->errors['emailTo'] = $this->modx->lexicon('formit.email_no_recipient');
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] '.$this->modx->lexicon('formit.email_no_recipient'));
            return false;
        }

        /* compile message */
        if (empty($tpl)) {
            $tpl = 'email';
            $f = '';
            foreach ($fields as $k => $v) {
                if ($k == 'nospam') continue;
                if (is_array($v) && !empty($v['name']) && isset($v['error']) && $v['error'] == UPLOAD_ERR_OK) {
                    $v = $v['name'];
                }
                $f .= '<strong>'.$k.'</strong>: '.$v.'<br />'."\n";
            }
            $fields['fields'] = $f;
        }
        $message = $this->formit->getChunk($tpl,$fields);

        /* load mail service */
        $this->modx->getService('mail', 'mail.modPHPMailer');
        $this->modx->mail->set(modMail::MAIL_BODY,$emailHtml ? nl2br($message) : $message);
        $this->modx->mail->set(modMail::MAIL_FROM, $emailFrom);
        $this->modx->mail->set(modMail::MAIL_FROM_NAME, $emailFromName);
        $this->modx->mail->set(modMail::MAIL_SENDER, $emailFrom);
        $this->modx->mail->set(modMail::MAIL_SUBJECT, $subject);

        /* handle file fields */
        foreach ($fields as $k => $v) {
            $attachmentIndex = 0;
            if (is_array($v) && !empty($v['tmp_name']) && isset($v['error']) && $v['error'] == UPLOAD_ERR_OK) {
                if (empty($v['name'])) {
                    $v['name'] = 'attachment'.$attachmentIndex;
                }
                $this->modx->mail->mailer->AddAttachment($v['tmp_name'],$v['name'],'base64',!empty($v['type']) ? $v['type'] : 'application/octet-stream');
                $attachmentIndex++;
            }
        }

        /* add to: with support for multiple addresses */
        $emailTo = explode(',',$emailTo);
        $emailToName = explode(',',$emailToName);
        $numAddresses = count($emailTo);
        for ($i=0;$i<$numAddresses;$i++) {
            $etn = !empty($emailToName[$i]) ? $emailToName[$i] : '';
            if (!empty($etn)) $etn = $this->_process($etn,$fields);
            $emailTo[$i] = $this->_process($emailTo[$i],$fields);
            if (!empty($emailTo[$i])) {
                $this->modx->mail->address('to',$emailTo[$i],$etn);
            }
        }

        /* reply to */
        $emailReplyTo = $this->modx->getOption('emailReplyTo',$this->formit->config,$emailFrom);
        $emailReplyTo = $this->_process($emailReplyTo,$fields);
        $emailReplyToName = $this->modx->getOption('emailReplyToName',$this->formit->config,$emailFromName);
        $emailReplyToName = $this->_process($emailReplyToName,$fields);
        if (!empty($emailReplyTo)) {
            $this->modx->mail->address('reply-to',$emailReplyTo,$emailReplyToName);
        }

        /* cc */
        $emailCC = $this->modx->getOption('emailCC',$this->formit->config,'');
        if (!empty($emailCC)) {
            $emailCCName = $this->modx->getOption('emailCCName',$this->formit->config,'');
            $emailCC = explode(',',$emailCC);
            $emailCCName = explode(',',$emailCCName);
            $numAddresses = count($emailCC);
            for ($i=0;$i<$numAddresses;$i++) {
                $etn = !empty($emailCCName[$i]) ? $emailCCName[$i] : '';
                if (!empty($etn)) $etn = $this->_process($etn,$fields);
                $emailCC[$i] = $this->_process($emailCC[$i],$fields);
                if (!empty($emailCC[$i])) {
                    $this->modx->mail->address('cc',$emailCC[$i],$etn);
                }
            }
        }

        /* bcc */
        $emailBCC = $this->modx->getOption('emailBCC',$this->formit->config,'');
        if (!empty($emailBCC)) {
            $emailBCCName = $this->modx->getOption('emailBCCName',$this->formit->config,'');
            $emailBCC = explode(',',$emailBCC);
            $emailBCCName = explode(',',$emailBCCName);
            $numAddresses = count($emailBCC);
            for ($i=0;$i<$numAddresses;$i++) {
                $etn = !empty($emailBCCName[$i]) ? $emailBCCName[$i] : '';
                if (!empty($etn)) $etn = $this->_process($etn,$fields);
                $emailBCC[$i] = $this->_process($emailBCC[$i],$fields);
                if (!empty($emailBCC[$i])) {
                    $this->modx->mail->address('bcc',$emailBCC[$i],$etn);
                }
            }
        }

        /* set HTML */
        $emailHtml = (boolean)$this->modx->getOption('emailHtml',$this->formit->config,true);
        $this->modx->mail->setHTML($emailHtml);

        /* send email */
        $sent = $this->modx->mail->send();
        $this->modx->mail->reset(array(
            modMail::MAIL_CHARSET => $this->modx->getOption('mail_charset',null,'UTF-8'),
            modMail::MAIL_ENCODING => $this->modx->getOption('mail_encoding',null,'8bit'),
        ));

        if (!$sent) {
            $this->errors[] = $this->modx->lexicon('formit.email_not_sent').' '.print_r($this->modx->mail->mailer->ErrorInfo,true);
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] '.$this->modx->lexicon('formit.email_not_sent').' '.print_r($this->modx->mail->mailer->ErrorInfo,true));
        }

        return $sent;
    }

    /**
     * Processes string and sets placeholders
     *
     * @param string $str The string to process
     * @param array $placeholders An array of placeholders to replace with values
     * @return string The parsed string
     */
    public function _process($str,array $placeholders = array()) {
        foreach ($placeholders as $k => $v) {
            $str = str_replace('[[+'.$k.']]',$v,$str);
        }
        return $str;
    }

    /**
     * Ensure the a field passes a spam filter.
     *
     * Properties:
     * - spamEmailFields - The email fields to check. A comma-delimited list.
     *
     * @access public
     * @param array $fields An array of cleaned POST fields
     * @return boolean True if email was successfully sent.
     */
    public function spam(array $fields = array()) {
        $passed = true;
        $spamEmailFields = $this->modx->getOption('spamEmailFields',$this->formit->config,'email');
        $emails = explode(',',$spamEmailFields);
        if ($this->modx->loadClass('stopforumspam.StopForumSpam',$this->formit->config['modelPath'],true,true)) {
            $sfspam = new StopForumSpam($this->modx);
            $checkIp = $this->modx->getOption('spamCheckIp',$this->formit->config,false);
            $ip = $checkIp ? $_SERVER['REMOTE_ADDR'] : '';
            foreach ($emails as $email) {
                $spamResult = $sfspam->check($ip,$fields[$email]);
                if (!empty($spamResult)) {
                    $spamFields = implode($this->modx->lexicon('formit.spam_marked')."\n<br />",$spamResult);
                    $this->errors[$email] = $this->modx->lexicon('formit.spam_blocked',array(
                        'fields' => $spamFields,
                    ));
                    $passed = false;
                }
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] Couldnt load StopForumSpam class.');
        }
        return $passed;
    }

    /**
     * Adds in reCaptcha support to FormIt
     * 
     * @access public
     * @param array $fields An array of cleaned POST fields
     * @return boolean True if email was successfully sent.
     */
    public function recaptcha(array $fields = array()) {
        $passed = false;
        $recaptcha = $this->formit->loadReCaptcha();
        if (empty($recaptcha->config[FormItReCaptcha::OPT_PRIVATE_KEY])) return false;

        $response = $recaptcha->checkAnswer($_SERVER['REMOTE_ADDR'],$_POST['recaptcha_challenge_field'],$_POST['recaptcha_response_field']);

        if (!$response->is_valid) {
            $this->errors['recaptcha'] = $this->modx->lexicon('recaptcha.incorrect',array(
                'error' => $response->error != 'incorrect-captcha-sol' ? $response->error : '',
            ));
        } else {
            $passed = true;
        }
        return $passed;
    }

    /**
     * Set a URL to redirect to after all hooks run successfully.
     *
     * @param string $url The URL to redirect to after all hooks execute
     */
    public function setRedirectUrl($url) {
        $this->redirectUrl = $url;
    }

    /**
     * Math field hook for anti-spam math input field.
     *
     * @access public
     * @param array $fields An array of cleaned POST fields
     * @return boolean True if email was successfully sent.
     */
    public function math(array $fields = array()) {
        $mathField = $this->modx->getOption('mathField',$this->formit->config,'math');
        if (empty($fields[$mathField])) { $this->errors[$mathField] = $this->modx->lexicon('formit.math_field_nf',array('field' => $mathField)); return false; }
        $op1Field = $this->modx->getOption('mathOp1Field',$this->formit->config,'op1');
        if (empty($fields[$op1Field])) { $this->errors[$mathField] = $this->modx->lexicon('formit.math_field_nf',array('field' => $op1Field)); return false; }
        $op2Field = $this->modx->getOption('mathOp2Field',$this->formit->config,'op2');
        if (empty($fields[$op2Field])) { $this->errors[$mathField] = $this->modx->lexicon('formit.math_field_nf',array('field' => $op2Field)); return false; }
        $operatorField = $this->modx->getOption('mathOperatorField',$this->formit->config,'operator');
        if (empty($fields[$operatorField])) { $this->errors[$mathField] = $this->modx->lexicon('formit.math_field_nf',array('field' => $operatorField)); return false; }

        $answer = false;
        $op1 = (int)$fields[$op1Field];
        $op2 = (int)$fields[$op2Field];
        switch ($fields[$operatorField]) {
            case '+': $answer = $op1 + $op2; break;
            case '-': $answer = $op1 - $op2; break;
            case '*': $answer = $op1 * $op2; break;
        }
        $guess = (int)$fields[$mathField];
        $passed = (boolean)($guess == $answer);
        if (!$passed) {
            $this->errors[$mathField] = $this->modx->lexicon('formit.math_incorrect');
        }
        return $passed;
    }
}