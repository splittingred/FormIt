<?php

namespace Sterc\FormIt\Hook;

use Sterc\FormIt\Service\StopForumSpam;

class Spam
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
     * Ensure the a field passes a spam filter.
     *
     * Properties:
     * - spamEmailFields - The email fields to check. A comma-delimited list.
     *
     * @param array $fields An array of cleaned POST fields
     *
     * @return bool True if email was successfully sent.
     */
    public function process($fields = [])
    {
        $passed = true;
        $spamEmailFields = $this->modx->getOption('spamEmailFields', $this->formit->config, 'email');
        $emails = explode(',', $spamEmailFields);

        $sfspam = new StopForumSpam($this->modx);
        $checkIp = $this->modx->getOption('spamCheckIp', $this->formit->config, false);
        $ip = $checkIp ? $_SERVER['REMOTE_ADDR'] : '';

        foreach ($emails as $email) {
            $spamResult = $sfspam->check($ip, $fields[$email]);
            if (!empty($spamResult)) {
                $spamFields = implode($this->modx->lexicon('formit.spam_marked')."\n<br />", $spamResult);
                $this->addError(
                    $email,
                    $this->modx->lexicon('formit.spam_blocked', array('fields' => $spamFields))
                );
                $passed = false;
            }
        }

        return $passed;
    }
}
