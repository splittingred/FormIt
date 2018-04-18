<?php

namespace Sterc\FormIt\Hook;

class Autoresponder
{
    /**
     * A reference to the hook instance.
     * @var \Sterc\FormIt\Hook\ $hook
     */
    public $hook;

    /**
     * A reference to the modX instance.
     * @var \modx $modx
     */
    public $modx;

    /**
     * An array of configuration properties
     * @var array $config
     */
    public $config = [];

    /**
     * A reference to the FormIt instance.
     * @var \Sterc\FormIt $formit
     */
    public $formit;

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
     * Send an autoresponder email of the form.
     *
     * Properties:
     *  fiarTpl Required. Tpl chunk for auto-response message.
     *  fiarSubject The subject of the email.
     *  fiarToField The name of the form field to use as the submitter's email. Defaults to "email".
     *  fiarFrom    Optional. If set, will specify the From: address for the email.
     *      Defaults to the `emailsender` system setting.
     *  fiarFromName    Optional. If set, will specify the From: name for the email.
     *  fiarSender  Optional. Specify the email Sender header. Defaults to the `emailsender` system setting.
     *  fiarHtml    Optional. Whether or not the email should be in HTML-format. Defaults to true.
     *  fiarReplyTo Required.An email to set as the reply-to.
     *  fiarReplyToName Optional. The name for the Reply-To field.
     *  fiarCC  A comma-separated list of emails to send via cc.
     *  fiarCCName  Optional. A comma-separated list of names to pair with the fiarCC values.
     *  fiarBCC A comma-separated list of emails to send via bcc.
     *  fiarBCCName Optional. A comma-separated list of names to pair with the fiarBCC values.
     *  fiarMultiWrapper    Wraps values submitted by checkboxes/multi-selects with this value.
     *      Defaults to just the value.
     *  fiarMultiSeparator  Separates checkboxes/multi-selects with this value. Defaults to a newline. ("\n")
     *  fiarFiles   Optional. Comma separated list of files to add as attachment to the email.
     *      You cannot use a url here, only a local filesystem path.
     *  fiarRequired    Optional. If set to false, the FormItAutoResponder hook doesn't stop
     *      when the field defined in 'fiarToField' is left empty. Defaults to true.
     *
     * @param array $fields An array of cleaned POST fields
     *
     * @return bool True if email was successfully sent.
     */
    public function process($fields = [])
    {
        $tpl = $this->modx->getOption('fiarTpl', $this->formit->config, 'fiDefaultFiarTpl', true);
        $mailFrom = $this->modx->getOption('fiarFrom', $this->formit->config, $this->modx->getOption('emailsender'));
        $mailFromName = $this->modx->getOption('fiarFromName', $this->formit->config, $this->modx->getOption('site_name'));
        $mailSender = $this->modx->getOption('fiarSender', $this->formit->config, $this->modx->getOption('emailsender'));
        $mailSubject = $this->modx->getOption('fiarSubject', $this->formit->config, '[[++site_name]] Auto-Responder');
        $mailSubject = str_replace(
            array('[[++site_name]]', '[[++emailsender]]'),
            array($this->modx->getOption('site_name'), $this->modx->getOption('emailsender')),
            $mailSubject
        );
        $fiarFiles = $this->modx->getOption('fiarFiles', $this->formit->config, false);
        $isHtml = $this->modx->getOption('fiarHtml', $this->formit->config, true);
        $toField = $this->modx->getOption('fiarToField', $this->formit->config, 'email');
        $multiSeparator = $this->modx->getOption('fiarMultiSeparator', $this->formit->config, "\n");
        $multiWrapper = $this->modx->getOption('fiarMultiWrapper', $this->formit->config, '[[+value]]');
        $required = $this->modx->getOption('fiarRequired', $this->formit->config, true);
        if (empty($fields[$toField])) {
            if ($required) {
                $this->modx->log(
                    \modX::LOG_LEVEL_ERROR,
                    '[FormIt] Auto-responder could not find field `'.$toField.'` in form submission.'
                );
                return false;
            } else {
                return true;
            }
        }

        /* handle checkbox and array fields */
        foreach ($fields as $k => $v) {
            if (is_array($v) && !empty($v['name']) && isset($v['error']) && $v['error'] == UPLOAD_ERR_OK) {
                $fields[$k] = $v['name'];
            } elseif (is_array($v)) {
                $vOpts = array();
                foreach ($v as $vKey => $vValue) {
                    if (is_string($vKey) && !empty($vKey)) {
                        $vKey = $k.'.'.$vKey;
                        $fields[$vKey] = $vValue;
                    } else {
                        $vOpts[] = str_replace('[[+value]]', $vValue, $multiWrapper);
                    }
                }
                $newValue = implode($multiSeparator, $vOpts);
                if (!empty($vOpts)) {
                    $fields[$k] = $newValue;
                }
            }
        }

        /* setup placeholders */
        $placeholders = $fields;
        $mailTo= $fields[$toField];

        $message = $this->formit->getChunk($tpl, $placeholders);
        $this->modx->parser->processElementTags('', $message, true, false);

        $this->modx->getService('mail', 'mail.modPHPMailer');
        $this->modx->mail->reset();
        $this->modx->mail->set(\modMail::MAIL_BODY, $message);
        $this->modx->mail->set(\modMail::MAIL_FROM, $this->hook->_process($mailFrom, $placeholders));
        $this->modx->mail->set(\modMail::MAIL_FROM_NAME, $this->hook->_process($mailFromName, $placeholders));
        $this->modx->mail->set(\modMail::MAIL_SENDER, $this->hook->_process($mailSender, $placeholders));
        $this->modx->mail->set(\modMail::MAIL_SUBJECT, $this->hook->_process($mailSubject, $placeholders));
        $this->modx->mail->address('to', $mailTo);
        $this->modx->mail->setHTML($isHtml);

        /* add attachments */
        if ($fiarFiles) {
            $fiarFiles = explode(',', $fiarFiles);
            foreach ($fiarFiles as $file) {
                $this->modx->mail->mailer->AddAttachment($file);
            }
        }

        /* reply to */
        $emailReplyTo = $this->modx->getOption('fiarReplyTo', $this->formit->config, $mailFrom);
        $emailReplyTo = $this->hook->_process($emailReplyTo, $fields);
        $emailReplyToName = $this->modx->getOption('fiarReplyToName', $this->formit->config, $mailFromName);
        $emailReplyToName = $this->hook->_process($emailReplyToName, $fields);
        if (!empty($emailReplyTo)) {
            $this->modx->mail->address('reply-to', $emailReplyTo, $emailReplyToName);
        }

        /* cc */
        $emailCC = $this->modx->getOption('fiarCC', $this->formit->config, '');
        if (!empty($emailCC)) {
            $emailCCName = $this->modx->getOption('fiarCCName', $this->formit->config, '');
            $emailCC = explode(',', $emailCC);
            $emailCCName = explode(',', $emailCCName);
            $numAddresses = count($emailCC);
            for ($i=0; $i < $numAddresses; $i++) {
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
        $emailBCC = $this->modx->getOption('fiarBCC', $this->formit->config, '');
        if (!empty($emailBCC)) {
            $emailBCCName = $this->modx->getOption('fiarBCCName', $this->formit->config, '');
            $emailBCC = explode(',', $emailBCC);
            $emailBCCName = explode(',', $emailBCCName);
            $numAddresses = count($emailBCC);
            for ($i=0; $i < $numAddresses; $i++) {
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

        if (!$this->formit->inTestMode) {
            if (!$this->modx->mail->send()) {
                $this->modx->log(
                    \modX::LOG_LEVEL_ERROR,
                    '[FormIt] An error occurred while trying to send
                      the auto-responder email: '.$this->modx->mail->mailer->ErrorInfo
                );
                return false;
            }
        }
        $this->modx->mail->reset();
        return true;
    }
}
