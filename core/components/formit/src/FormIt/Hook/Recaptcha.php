<?php

namespace Sterc\FormIt\Hook;

use Sterc\FormIt\Service\RecaptchaService;

class Recaptcha
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
     * Adds in reCaptcha support to FormIt
     *
     *
     * @return bool True if recaptcha has passed
     */
    public function process()
    {
        $passed = false;
        /** @var RecaptchaService $reCaptcha */
        $reCaptcha = $this->formit->request->loadReCaptcha();
        if (empty($reCaptcha->config[RecaptchaService::OPT_PRIVATE_KEY])) {
            $this->hook->addError('recaptcha', $this->modx->lexicon('recaptcha.no_api_key'));
            return false;
        }

        $response = $reCaptcha->checkAnswer(
            $_SERVER['REMOTE_ADDR'],
            $_POST['recaptcha_challenge_field'],
            $_POST['recaptcha_response_field']
        );

        if (!$response->is_valid) {
            $this->hook->addError('recaptcha', $this->modx->lexicon('recaptcha.incorrect', array(
                'error' => $response->error != 'incorrect-captcha-sol' ? $response->error : '',
            )));
        } else {
            $passed = true;
        }

        return $passed;
    }
}
