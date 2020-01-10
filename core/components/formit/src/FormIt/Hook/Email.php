<?php

namespace Sterc\FormIt\Hook;

class Email
{
    /**
     * A reference to the hook instance.
     * @var \Sterc\FormIt\Hook\ $hook
     */
    public $hook = null;

    /**
     * A reference to the modX instance.
     * @var \modx $modx
     */
    public $modx = null;

    /**
     * An array of configuration properties
     * @var array $config
     */
    public $config = [];

    /**
     * A reference to the FormIt instance.
     * @var \Sterc\FormIt $formit
     */
    public $formit = null;

    /**
     * @param \Sterc\FormIt\Hook $hook
     * @param array $config
     */
    public function __construct($hook, array $config = array())
    {
        $this->hook =& $hook;
        $this->formit =& $hook->formit;
        $this->modx = $hook->formit->modx;
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Send an email of the form.
     *
     * Properties:
     *  emailTpl - The chunk name of the chunk that will be the email template.
     *      This will send the values of the form as placeholders.
     *  emailTo - A comma separated list of email addresses to send to
     *  emailToName - A comma separated list of names to pair with addresses.
     *  emailFrom - The From: email address. Defaults to either the email
     *      field or the emailsender setting.
     *  emailFromName - The name of the From: user.
     *  emailSubject - The subject of the email.
     *  emailHtml - Boolean, if true, email will be in HTML mode.
     *
     * @param array $fields An array of cleaned POST fields
     *
     * @return bool True if email was successfully sent.
     */
    public function process($fields = [])
    {
        $tpl = $this->modx->getOption('emailTpl', $this->formit->config, '');
        $emailHtml = (bool) $this->modx->getOption('emailHtml', $this->formit->config, true);
        $emailConvertNewlines = (bool) $this->modx->getOption('emailConvertNewlines', $this->formit->config, false);

        /* get from name */
        $emailFrom = $this->modx->getOption('emailFrom', $this->formit->config, '');
        if (empty($emailFrom)) {
            $emailFrom = !empty($fields['email']) ? $fields['email'] : $this->modx->getOption('emailsender');
        }
        $emailFrom = $this->hook->_process($emailFrom, $fields);
        $emailFromName = $this->modx->getOption('emailFromName', $this->formit->config, $this->modx->getOption('site_name', null, $emailFrom));
        $emailFromName = $this->hook->_process($emailFromName, $fields);

        /* get returnPath */
        $emailReturnPath = $this->modx->getOption('emailReturnPath', $this->formit->config, '');
        if (empty($emailReturnPath)) {
            $emailReturnPath = $emailFrom;
        }
        $emailReturnPath = $this->hook->_process($emailReturnPath, $fields);

        /* get subject */
        $useEmailFieldForSubject = $this->modx->getOption('emailUseFieldForSubject', $this->formit->config, true);
        if (!empty($fields['subject']) && $useEmailFieldForSubject) {
            $subject = $fields['subject'];
        } else {
            $subject = $this->modx->getOption('emailSubject', $this->formit->config, '');
        }
        $subject = $this->hook->_process($subject, $fields);

        /* check email to */
        $emailTo = $this->modx->getOption('emailTo', $this->formit->config, '');
        $emailToName = $this->modx->getOption('emailToName', $this->formit->config, $emailTo);
        if (empty($emailTo)) {
            $this->hook->errors['emailTo'] = $this->modx->lexicon('formit.email_no_recipient');
            $this->modx->log(\modX::LOG_LEVEL_ERROR, '[FormIt] '.$this->modx->lexicon('formit.email_no_recipient'));
            return false;
        }

        /* compile message */
        $origFields = $fields;
        if (empty($tpl)) {
            $tpl = 'fiDefaultEmailTpl';
            $f = [];
            $multiSeparator = $this->modx->getOption('emailMultiSeparator', $this->formit->config, "\n");
            $multiWrapper = $this->modx->getOption('emailMultiWrapper', $this->formit->config, "[[+value]]");

            foreach ($fields as $k => $v) {
                if ($k == 'nospam') {
                    continue;
                }
                if (is_array($v) && !empty($v['name']) && isset($v['error']) && $v['error'] == UPLOAD_ERR_OK) {
                    $v = $v['name'];
                    $f[$k] = '<strong>'.$k.'</strong>: '.$v.'<br />';
                } elseif (is_array($v)) {
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
                } elseif (is_array($v)) {
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
                    if (!empty($vOpts)) {
                        $fields[$k] = $v;
                    }
                }
            }
        }

        $message = $this->formit->getChunk($tpl, $fields);
        $message = $this->hook->_process($message, $this->config);

        /* load mail service */
        $this->modx->getService('mail', 'mail.modPHPMailer');

        /* set HTML */
        $this->modx->mail->setHTML($emailHtml);

        /* set email main properties */
        $this->modx->mail->set(\modMail::MAIL_BODY, $emailHtml && $emailConvertNewlines ? nl2br($message) : $message);
        $this->modx->mail->set(\modMail::MAIL_FROM, $emailFrom);
        $this->modx->mail->set(\modMail::MAIL_FROM_NAME, $emailFromName);
        $this->modx->mail->set(\modMail::MAIL_SENDER, $emailReturnPath);
        $this->modx->mail->set(\modMail::MAIL_SUBJECT, $subject);

        /* handle file fields */
        if ($this->modx->getOption('attachFilesToEmail', $this->config, true)) {
            $attachmentIndex = 0;
            foreach ($origFields as $k => $v) {
                if (is_array($v) && !empty($v['tmp_name'])) {
                    if (is_array($v['name'])) {
                        for ($i = 0; $i < count($v['name']); ++$i) {
                            if (isset($v['error'][$i]) && $v['error'][$i] == UPLOAD_ERR_OK) {
                                if (empty($v['name'][$i])) {
                                    $v['name'][$i] = 'attachment' . $attachmentIndex;
                                }
                                $this->modx->mail->mailer->addAttachment(
                                    $v['tmp_name'][$i],
                                    $v['name'][$i],
                                    'base64',
                                    !empty($v['type'][$i]) ? $v['type'][$i] : 'application/octet-stream'
                                );
                                $attachmentIndex++;
                            }
                        }
                    } else {
                        if (isset($v['error']) && $v['error'] == UPLOAD_ERR_OK) {
                            if (empty($v['name'])) {
                                $v['name'] = 'attachment' . $attachmentIndex;
                            }
                            $this->modx->mail->mailer->addAttachment(
                                $v['tmp_name'],
                                $v['name'],
                                'base64',
                                !empty($v['type']) ? $v['type'] : 'application/octet-stream'
                            );
                            $attachmentIndex++;
                        }
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
                $etn = $this->hook->_process($etn, $fields);
            }
            $emailTo[$i] = $this->hook->_process($emailTo[$i], $fields);
            if (!empty($emailTo[$i])) {
                $this->modx->mail->address('to', $emailTo[$i], $etn);
            }
        }

        /* reply to */
        $emailReplyTo = $this->modx->getOption('emailReplyTo', $this->formit->config, '');
        if (empty($emailReplyTo)) {
            $emailReplyTo = !empty($fields['email']) ? $fields['email'] : $emailFrom;
        }
        $emailReplyTo = $this->hook->_process($emailReplyTo, $fields);
        $emailReplyToName = $this->modx->getOption('emailReplyToName', $this->formit->config, $emailFromName);
        $emailReplyToName = $this->hook->_process($emailReplyToName, $fields);
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
                    $etn = $this->hook->_process($etn, $fields);
                }
                $emailCC[$i] = $this->hook->_process($emailCC[$i], $fields);
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
                    $etn = $this->hook->_process($etn, $fields);
                }
                $emailBCC[$i] = $this->hook->_process($emailBCC[$i], $fields);
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
            \modMail::MAIL_CHARSET => $this->modx->getOption('mail_charset', null, 'UTF-8'),
            \modMail::MAIL_ENCODING => $this->modx->getOption('mail_encoding', null, '8bit'),
        ));

        if (!$sent) {
            $this->hook->errors[] = $this->modx->lexicon('formit.email_not_sent').' '.print_r($this->modx->mail->mailer->ErrorInfo, true);
            $this->modx->log(\modX::LOG_LEVEL_ERROR, '[FormIt] '.$this->modx->lexicon('formit.email_not_sent').' '.print_r($this->modx->mail->mailer->ErrorInfo, true));
        }

        return $sent;
    }
}
