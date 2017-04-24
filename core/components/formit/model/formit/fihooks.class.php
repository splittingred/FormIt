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
 * Base Hooks handling class. Hooks can be used to run scripts prior to loading the form, or after a form has been
 * submitted.
 *
 * Hooks can be either a predefined list by FormIt, or custom MODX Snippets. They can also be chained to allow for
 * order-of-execution processing. Returning false from a Hook will end the chain. Returning false in a postHook will
 * prevent the form from being further submitted.
 *
 * @package formit
 */
class fiHooks {
    /**
     * A collection of all the processed errors so far.
     * @var array $errors
     * @access public
     */
    public $errors = array();
    /**
     * A collection of all the processed hooks so far.
     * @var array $hooks
     * @access public
     */
    public $hooks = array();
    /**
     * A reference to the modX instance.
     * @var modX $modx
     * @access public
     */
    public $modx = null;
    /**
     * A reference to the FormIt instance.
     * @var FormIt $formit
     * @access public
     */
    public $formit = null;
    /**
     * If a hook redirects, it needs to set this var to use proper order of execution on redirects/stores
     * @var string
     * @access public
     */
    public $redirectUrl = null;

    /**
     * The current stored and parsed fields for the FormIt call.
     * @var array $fields
     */
    public $fields = array();

    /**
     * The type of Hook request that this represents
     * @var string $type
     */
    public $type;

    /**
     * The constructor for the fiHooks class
     *
     * @param FormIt &$formit A reference to the FormIt class instance.
     * @param array $config Optional. An array of configuration parameters.
     * @param string $type The type of hooks this service class is loading
     * @return fiHooks
     */
    function __construct(FormIt &$formit,array $config = array(),$type = '') {
        $this->formit =& $formit;
        $this->modx =& $formit->modx;
        $this->config = array_merge(array(
            'placeholderPrefix' => 'fi.',
            'errTpl' => '<span class="error">[[+error]]</span>',

            'mathField' => 'math',
            'mathOp1Field' => 'op1',
            'mathOp2Field' => 'op2',
            'mathOperatorField' => 'operator',
            'hookErrorJsonOutputPlaceholder' => ''
        ),$config);
        $this->type = $type;
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
     * @param string $hookName The name of the hook. May be a Snippet name.
     * @param array $fields The fields and values of the form.
     * @param array $customProperties Any other custom properties to load into a custom hook.
     * @return boolean True if hook was successful.
     */
    public function load($hookName,array $fields = array(),array $customProperties = array()) {
        $success = false;
        if (!empty($fields)) $this->fields =& $fields;
        $this->hooks[] = $hookName;

        $reserved = array('load','_process','__construct','getErrorMessage');
        if (method_exists($this,$hookName) && !in_array($hookName,$reserved)) {
            /* built-in hooks */
            $success = $this->$hookName($this->fields);

        /** @var modSnippet $snippet */
        } else if ($snippet = $this->modx->getObject('modSnippet',array('name' => $hookName))) {
            /* custom snippet hook */
            $properties = array_merge($this->formit->config,$customProperties);
            $properties['formit'] =& $this->formit;
            $properties['hook'] =& $this;
            $properties['fields'] = $this->fields;
            $properties['errors'] =& $this->errors;
            $success = $snippet->process($properties);
        } else {
            /* search for a file-based hook */
            $this->modx->parser->processElementTags('',$hookName,true,true);
            if (file_exists($hookName)) {
                $success = $this->_loadFileBasedHook($hookName,$customProperties);
            } else {
                /* no hook found */
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] Could not find hook "'.$hookName.'".');
                $success = true;
            }
        }

        if (is_array($success) && !empty($success)) {
            $this->errors = array_merge($this->errors,$success);
            $success = false;
        } else if ($success != true) {
            if (!isset($this->errors[$hookName])) $this->errors[$hookName] = '';
            $this->errors[$hookName] .= ' '.$success;
            $success = false;
        }
        return $success;
    }

    /**
     * Attempt to load a file-based hook given a name
     * @param string $path The absolute path of the hook file
     * @param array $customProperties An array of custom properties to run with the hook
     * @return boolean True if the hook succeeded
     */
    private function _loadFileBasedHook($path,array $customProperties = array()) {
        $scriptProperties = array_merge($this->formit->config,$customProperties);
        $formit =& $this->formit;
        $hook =& $this;
        $fields = $this->fields;
        $errors =& $this->errors;
        $modx =& $this->modx;
        $success = false;
        try {
            $success = include $path;
        } catch (Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] '.$e->getMessage());
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
        if (!isset($this->errors[$key])) $this->errors[$key] = '';
        $this->errors[$key] .= $value;
        return $this->errors[$key];
    }

    /**
     * See if there are any errors in the stack.
     *
     * @return boolean
     */
    public function hasErrors() {
        return !empty($this->errors);
    }

    /**
     * Get all errors for this current request
     * 
     * @return array
     */
    public function getErrors() {
        return $this->errors;
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
        $contextKey = $this->modx->context->get('key');
        $resource = $this->modx->getObject('modResource',$this->formit->config['redirectTo']);
        if ($resource) {
            $contextKey = $resource->get('context_key');
        }
        if (!is_numeric($this->formit->config['redirectTo']) &&
            isset($fields[$this->formit->config['redirectTo']]) &&
            is_numeric($fields[$this->formit->config['redirectTo']]) 
            ) {
            $url = $this->modx->makeUrl($fields[$this->formit->config['redirectTo']],$contextKey,$redirectParams,'full');
        } elseif (!is_numeric($this->formit->config['redirectTo']) &&
            substr($this->formit->config['redirectTo'], 0, 4 ) === "http"
            ) {
            $url = $this->formit->config['redirectTo'];
        } else {
            $url = $this->modx->makeUrl($this->formit->config['redirectTo'],$contextKey,$redirectParams,'full');
        }
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
    public function email(array $fields = array())
    {
        $tpl = $this->modx->getOption('emailTpl', $this->formit->config, '');
        $emailHtml = (boolean)$this->modx->getOption('emailHtml', $this->formit->config, true);
        $emailConvertNewlines = (boolean)$this->modx->getOption('emailConvertNewlines', $this->formit->config, false);

        /* get from name */
        $emailFrom = $this->modx->getOption('emailFrom', $this->formit->config, '');
        if (empty($emailFrom)) {
            $emailFrom = !empty($fields['email']) ? $fields['email'] : $this->modx->getOption('emailsender');
        }
        $emailFrom = $this->_process($emailFrom, $fields);
        $emailFromName = $this->modx->getOption('emailFromName', $this->formit->config, $this->modx->getOption('site_name', null, $emailFrom));
        $emailFromName = $this->_process($emailFromName, $fields);

        /* get returnPath */
        $emailReturnPath = $this->modx->getOption('emailReturnPath', $this->formit->config, '');
        if (empty($emailReturnPath)) {
            $emailReturnPath = $emailFrom;
        }
        $emailReturnPath = $this->_process($emailReturnPath, $fields);

        /* get subject */
        $useEmailFieldForSubject = $this->modx->getOption('emailUseFieldForSubject', $this->formit->config, true);
        if (!empty($fields['subject']) && $useEmailFieldForSubject) {
            $subject = $fields['subject'];
        } else {
            $subject = $this->modx->getOption('emailSubject', $this->formit->config, '');
        }
        $subject = $this->_process($subject, $fields);

        /* check email to */
        $emailTo = $this->modx->getOption('emailTo', $this->formit->config, '');
        $emailToName = $this->modx->getOption('emailToName', $this->formit->config, $emailTo);
        if (empty($emailTo)) {
            $this->errors['emailTo'] = $this->modx->lexicon('formit.email_no_recipient');
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[FormIt] '.$this->modx->lexicon('formit.email_no_recipient'));
            return false;
        }

        /* compile message */
        $origFields = $fields;
        if (empty($tpl)) {
            $tpl = 'fiDefaultEmailTpl';
            $f = '';
            $multiSeparator = $this->modx->getOption('emailMultiSeparator', $this->formit->config, "\n");
            $multiWrapper = $this->modx->getOption('emailMultiWrapper', $this->formit->config, "[[+value]]");

            foreach ($fields as $k => $v) {
                if ($k == 'nospam') {
                    continue;
                }
                if (is_array($v) && !empty($v['name']) && isset($v['error']) && $v['error'] == UPLOAD_ERR_OK) {
                    $v = $v['name'];
                    $f[$k] = '<strong>'.$k.'</strong>: '.$v.'<br />';
                } else if (is_array($v)) {
                    $vOpts = array();
                    foreach ($v as $vKey => $vValue) {
                        if (is_string($vKey) && !empty($vKey)) {
                            $vKey = $k.'.'.$vKey;
                            $f[$vKey] = '<strong>'.$vKey.'</strong>: '.$vValue.'<br />';
                        } else {
                            $vOpts[] = str_replace('[[+value]]', $vValue, $multiWrapper);
                        }
                    }
                    $newValue = implode($multiSeparator, $vOpts);
                    if (!empty($vOpts)) {
                        $f[$k] = '<strong>'.$k.'</strong>:'.$newValue.'<br />';
                    }
                } else {
                    $f[$k] = '<strong>'.$k.'</strong>: '.$v.'<br />';
                }
            }
            $fields['fields'] = implode("\n", $f);
        } else {
            /* handle file/checkboxes in email tpl */
            $multiSeparator = $this->modx->getOption('emailMultiSeparator', $this->formit->config, "\n");
            if (empty($multiSeparator)) {
                $multiSeparator = "\n";
            }
            if ($multiSeparator == '\n') {
                $multiSeparator = "\n"; /* allow for inputted newlines */
            }
            $multiWrapper = $this->modx->getOption('emailMultiWrapper', $this->formit->config, "[[+value]]");
            if (empty($multiWrapper)) {
                $multiWrapper = '[[+value]]';
            }
            
            foreach ($fields as $k => &$v) {
                if (is_array($v) && !empty($v['name']) && isset($v['error']) && $v['error'] == UPLOAD_ERR_OK) {
                    $v = $v['name'];
                } else if (is_array($v)) {
                    $vOpts = array();
                    foreach ($v as $vKey => $vValue) {
                        if (is_string($vKey) && !empty($vKey)) {
                            $vKey = $k.'.'.$vKey;
                            $fields[$vKey] = $vValue;
                            unset($fields[$k]);
                        } else {
                            $vOpts[] = str_replace('[[+value]]', $vValue, $multiWrapper);
                        }
                    }
                    $v = implode($multiSeparator, $vOpts);
                }
            }
        }

        $message = $this->formit->getChunk($tpl, $fields);
        $message = $this->_process($message, $this->config);

        /* load mail service */
        $this->modx->getService('mail', 'mail.modPHPMailer');

        /* set HTML */
        $this->modx->mail->setHTML($emailHtml);

        /* set email main properties */
        $this->modx->mail->set(modMail::MAIL_BODY, $emailHtml && $emailConvertNewlines ? nl2br($message) : $message);
        $this->modx->mail->set(modMail::MAIL_FROM, $emailFrom);
        $this->modx->mail->set(modMail::MAIL_FROM_NAME, $emailFromName);
        $this->modx->mail->set(modMail::MAIL_SENDER, $emailReturnPath);
        $this->modx->mail->set(modMail::MAIL_SUBJECT, $subject);

        /* handle file fields */
        $attachmentIndex = 0;
        foreach ($origFields as $k => $v) {
            if (is_array($v) && !empty($v['tmp_name'])) {
                if (is_array($v['name'])) {
                    for ($i = 0; $i < count($v['name']); ++$i) {
                        if (isset($v['error'][$i]) && $v['error'][$i] == UPLOAD_ERR_OK) {
                            if (empty($v['name'][$i])) {
                                $v['name'][$i] = 'attachment'.$attachmentIndex;
                            }
                            $this->modx->mail->mailer->addAttachment($v['tmp_name'][$i], $v['name'][$i], 'base64', !empty($v['type'][$i]) ? $v['type'][$i] : 'application/octet-stream');
                            $attachmentIndex++;
                        }
                    }
                } else {
                    if (isset($v['error']) && $v['error'] == UPLOAD_ERR_OK) {
                        if (empty($v['name'])) {
                            $v['name'] = 'attachment'.$attachmentIndex;
                        }
                        $this->modx->mail->mailer->addAttachment($v['tmp_name'], $v['name'], 'base64', !empty($v['type']) ? $v['type'] : 'application/octet-stream');
                        $attachmentIndex++;
                    }
                }
            }
        }
        
        /* add to: with support for multiple addresses */
        $emailTo = explode(',', $emailTo);
        $emailToName = explode(',', $emailToName);
        $numAddresses = count($emailTo);
        for ($i = 0; $i < $numAddresses; $i++) {
            $etn = !empty($emailToName[$i]) ? $emailToName[$i] : '';
            if (!empty($etn)) {
                $etn = $this->_process($etn, $fields);
            }
            $emailTo[$i] = $this->_process($emailTo[$i], $fields);
            if (!empty($emailTo[$i])) {
                $this->modx->mail->address('to', $emailTo[$i], $etn);
            }
        }

        /* reply to */
        $emailReplyTo = $this->modx->getOption('emailReplyTo', $this->formit->config, '');
        if (empty($emailReplyTo)) {
            $emailReplyTo = !empty($fields['email']) ? $fields['email'] : $emailFrom;
        }
        $emailReplyTo = $this->_process($emailReplyTo, $fields);
        $emailReplyToName = $this->modx->getOption('emailReplyToName', $this->formit->config, $emailFromName);
        $emailReplyToName = $this->_process($emailReplyToName, $fields);
        if (!empty($emailReplyTo)) {
            $this->modx->mail->address('reply-to', $emailReplyTo, $emailReplyToName);
        }

        /* cc */
        $emailCC = $this->modx->getOption('emailCC', $this->formit->config, '');
        if (!empty($emailCC)) {
            $emailCCName = $this->modx->getOption('emailCCName', $this->formit->config, '');
            $emailCC = explode(',', $emailCC);
            $emailCCName = explode(',', $emailCCName);
            $numAddresses = count($emailCC);
            for ($i = 0; $i < $numAddresses; $i++) {
                $etn = !empty($emailCCName[$i]) ? $emailCCName[$i] : '';
                if (!empty($etn)) {
                    $etn = $this->_process($etn, $fields);
                }
                $emailCC[$i] = $this->_process($emailCC[$i], $fields);
                if (!empty($emailCC[$i])) {
                    $this->modx->mail->address('cc', $emailCC[$i], $etn);
                }
            }
        }

        /* bcc */
        $emailBCC = $this->modx->getOption('emailBCC', $this->formit->config, '');
        if (!empty($emailBCC)) {
            $emailBCCName = $this->modx->getOption('emailBCCName', $this->formit->config, '');
            $emailBCC = explode(',', $emailBCC);
            $emailBCCName = explode(',', $emailBCCName);
            $numAddresses = count($emailBCC);
            for ($i = 0; $i < $numAddresses; $i++) {
                $etn = !empty($emailBCCName[$i]) ? $emailBCCName[$i] : '';
                if (!empty($etn)) {
                    $etn = $this->_process($etn, $fields);
                }
                $emailBCC[$i] = $this->_process($emailBCC[$i], $fields);
                if (!empty($emailBCC[$i])) {
                    $this->modx->mail->address('bcc', $emailBCC[$i], $etn);
                }
            }
        }

        /* send email */
        if (!$this->formit->inTestMode) {
            $sent = $this->modx->mail->send();
        } else {
            $sent = true;
        }
        $this->modx->mail->reset(array(
            modMail::MAIL_CHARSET => $this->modx->getOption('mail_charset', null, 'UTF-8'),
            modMail::MAIL_ENCODING => $this->modx->getOption('mail_encoding', null, '8bit'),
        ));

        if (!$sent) {
            $this->errors[] = $this->modx->lexicon('formit.email_not_sent').' '.print_r($this->modx->mail->mailer->ErrorInfo, true);
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[FormIt] '.$this->modx->lexicon('formit.email_not_sent').' '.print_r($this->modx->mail->mailer->ErrorInfo, true));
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
        if (is_string($str)) {
            foreach ($placeholders as $k => $v) {
                if (is_scalar($k) && is_scalar($v)) {
                    $str = str_replace('[[+'.$k.']]',$v,$str);
                }
            }
        }
        $this->modx->parser->processElementTags('',$str,true,false);
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
                    $this->addError($email,$this->modx->lexicon('formit.spam_blocked',array(
                        'fields' => $spamFields,
                    )));
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
    public function recaptcha(array $fields = array())
    {
        $passed = false;
        /** @var FormItReCaptcha $reCaptcha */
        $reCaptcha = $this->formit->request->loadReCaptcha();
        if (empty($reCaptcha->config[FormItReCaptcha::OPT_PRIVATE_KEY])) {
            return false;
        }

        $response = $reCaptcha->checkAnswer($_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);

        if (!$response->is_valid) {
            $this->addError('recaptcha', $this->modx->lexicon('recaptcha.incorrect', array(
                'error' => $response->error != 'incorrect-captcha-sol' ? $response->error : '',
            )));
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
    public function setRedirectUrl($url)
    {
        $this->redirectUrl = $url;
    }

    /**
     * Get the specified redirection url
     *
     * @return null|string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * Math field hook for anti-spam math input field.
     *
     * @access public
     * @param array $fields An array of cleaned POST fields
     * @return boolean True if email was successfully sent.
     */
    public function math(array $fields = array())
    {
        $mathField = $this->modx->getOption('mathField', $this->config, 'math');
        if (!isset($fields[$mathField])) {
            $this->errors[$mathField] = $this->modx->lexicon('formit.math_field_nf', array('field' => $mathField));
            return false;
        }
        if (empty($fields[$mathField])) {
            $this->errors[$mathField] = $this->modx->lexicon('formit.field_required', array('field' => $mathField));
            return false;
        }

        $passed = false;
        if (isset($_SESSION['formitMath']['op1']) && isset($_SESSION['formitMath']['op2']) && isset($_SESSION['formitMath']['operator'])) {
            $answer = false;
            $op1 = $_SESSION['formitMath']['op1'];
            $op2 = $_SESSION['formitMath']['op2'];
            switch ($_SESSION['formitMath']['operator']) {
                case '+':
                    $answer = $op1 + $op2;
                    break;
                case '-':
                    $answer = $op1 - $op2;
                    break;
                case '*':
                    $answer = $op1 * $op2;
                    break;
            }
            $guess = (int)$fields[$mathField];
            $passed = (boolean)($guess == $answer);
        }
        if (!$passed) {
            $this->addError($mathField, $this->modx->lexicon('formit.math_incorrect'));
        }
        return $passed;
    }

    /**
     * Process any errors returned by the hooks and set them to placeholders
     * @return void
     */
    public function processErrors()
    {
        $errors = array();
        $jsonerrors = array();
        $jsonOutputPlaceholder = $this->config['hookErrorJsonOutputPlaceholder'];
        if (!empty($jsonOutputPlaceholder)) {
            $jsonerrors = array(
                'errors' => array(),
                'success' => false,
                'message' => '',
            );
        }
        
        $placeholderErrors = $this->getErrors();
        foreach ($placeholderErrors as $key => $error) {
            $errors[$key] = str_replace('[[+error]]', $error, $this->config['errTpl']);
            if (!empty($jsonOutputPlaceholder)) {
                $jsonerrors['errors'][$key] = $errors[$key];
            }
        }
        $this->modx->toPlaceholders($errors, $this->config['placeholderPrefix'].'error');

        $errorMsg = $this->getErrorMessage();
        if (!empty($errorMsg)) {
            $this->modx->setPlaceholder($this->config['placeholderPrefix'].'error_message', $errorMsg);
            if (!empty($jsonOutputPlaceholder)) {
                $jsonerrors['message'] = $errorMsg;
            }
        }
        if (!empty($jsonOutputPlaceholder)) {
            $jsonoutput = $this->modx->toJSON($jsonerrors);
            $this->modx->setPlaceholder($jsonOutputPlaceholder, $jsonoutput);
        }
    }

    /**
     * Gather fields and set them into placeholders for pre-fetching
     * @return array
     */
    public function gatherFields() {
        if (empty($this->fields)) return array();

        $fs = $this->getValues();
        /* better handling of checkbox values when input name is an array[] */
        foreach ($fs as $f => $v) {
            if (is_array($v)) { $v = implode(',',$v); }
            $fs[$f] = $v;
        }
        $this->modx->toPlaceholders($fs,$this->config['placeholderPrefix'],'');
        
        return $this->getValues();
    }
}
