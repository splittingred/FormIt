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
 * reCaptcha modX service class.
 *
 * Based off of recaptchalib.php by Mike Crawford and Ben Maurer. Changes include converting to OOP and making a class.
 *
 * @package formit
 * @subpackage recaptcha
 */
if (!class_exists('FormItReCaptcha')) {
class FormItReCaptcha {
    const API_SERVER = 'http://www.google.com/recaptcha/api/';
    const API_SECURE_SERVER = 'https://www.google.com/recaptcha/api/';
    const VERIFY_SERVER = 'www.google.com';
    const OPT_PRIVATE_KEY = 'privateKey';
    const OPT_PUBLIC_KEY = 'publicKey';
    const OPT_USE_SSL = 'use_ssl';

    /** @var modX $modX */
    public $modx;
    /** @var FormIt $formit */
    public $formit;

    function __construct(FormIt &$formit,array $config = array()) {
        $this->formit =& $formit;
        $this->modx =& $formit->modx;
        $this->modx->lexicon->load('formit:recaptcha');
        $this->config = array_merge(array(
            FormItReCaptcha::OPT_PRIVATE_KEY => $this->modx->getOption('formit.recaptcha_private_key',$config,''),
            FormItReCaptcha::OPT_PUBLIC_KEY => $this->modx->getOption('formit.recaptcha_public_key',$config,''),
            FormItReCaptcha::OPT_USE_SSL => $this->modx->getOption('formit.recaptcha_use_ssl',$config,false),
        ),$config);
    }

    /**
     * Encodes the given data into a query string format
     * @param $data - array of string elements to be encoded
     * @return string - encoded request
     */
    protected function qsencode($data) {
        $req = '';
        foreach ($data as $key => $value) {
            $req .= $key . '=' . urlencode( stripslashes($value) ) . '&';
        }

        // Cut the last '&'
        $req=substr($req,0,strlen($req)-1);
        return $req;
    }

    /**
     * Submits an HTTP POST to a reCAPTCHA server
     * @param string $host
     * @param string $path
     * @param array $data
     * @param int $port
     * @return array response
     */
    protected function httpPost($host, $path, array $data = array(), $port = 80) {
        $data['privatekey'] = $this->config[FormItReCaptcha::OPT_PRIVATE_KEY];
        $req = $this->qsencode($data);

        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $http_request .= "Content-Length: " . strlen($req) . "\r\n";
        $http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
        $http_request .= "\r\n";
        $http_request .= $req;

        $response = '';
        if(false == ($fs = @fsockopen($host, $port, $errno, $errstr, 10))) {
            return 'Could not open socket';
        }

        fwrite($fs, $http_request);
        while (!feof($fs)) {
            $response .= fgets($fs, 1160); // One TCP-IP packet
        }
        fclose($fs);
        $response = explode("\r\n\r\n", $response, 2);

        return $response;
    }

    /**
     * Gets the challenge HTML (javascript and non-javascript version).
     * This is called from the browser, and the resulting reCAPTCHA HTML widget
     * is embedded within the HTML form it was called from.
     *
     * @param array $scriptProperties
     * @return string - The HTML to be embedded in the user's form.
     */
    public function render($scriptProperties = array()) {
        if (empty($this->config[FormItReCaptcha::OPT_PUBLIC_KEY])) {
            return $this->error($this->modx->lexicon('recaptcha.no_api_key'));
        }

        /* use ssl or not */
        $server = !empty($this->config[FormItReCaptcha::OPT_USE_SSL]) ? FormItReCaptcha::API_SECURE_SERVER : FormItReCaptcha::API_SERVER;

        $opt = $this->getOptions($scriptProperties);
        $html = '<script type="text/javascript">var RecaptchaOptions = '.$this->modx->toJSON($opt).';</script><script type="text/javascript" src="'. $server . 'challenge?k=' . $this->config[FormItReCaptcha::OPT_PUBLIC_KEY] . '"></script>
<noscript><div>
        <object data="'. $server . 'noscript?k=' . $this->config[FormItReCaptcha::OPT_PUBLIC_KEY] . '" height="'.$opt['height'].'" width="'.$opt['width'].'" style="width: '.$opt['width'].'px; height: '.$opt['height'].'px;"></object>
        <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
        <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/></div>
</noscript>';
        $this->modx->setPlaceholder('formit.recaptcha_html',$html);
        $this->modx->setPlaceholder($scriptProperties['placeholderPrefix'].'recaptcha_html',$html);
        return $html;
    }

    /**
     * Get options for reCaptcha from snippet
     *
     * @param array $scriptProperties
     * @return array|void
     */
    public function getOptions(array $scriptProperties = array()) {
        $opt = $this->modx->getOption('recaptchaJs',$scriptProperties,'{}');
        $opt = $this->modx->fromJSON($opt);
        if (empty($opt)) $opt = array();

        /* backwards compat */
        $backwardOpt = array(
            'theme' => $this->modx->getOption('recaptchaTheme',$scriptProperties,'clean'),
            'width' => $this->modx->getOption('recaptchaWidth',$scriptProperties,500),
            'height' => $this->modx->getOption('recaptchaHeight',$scriptProperties,300),
            'lang' => $this->modx->getOption('cultureKey',null,'en'),
        );
        $opt = array_merge($backwardOpt,$opt);
        
        return $opt;
    }

    /**
     * State there is an error with reCaptcha
     * @param string $message
     * @return string
     */
    protected function error($message = '') {
        $response = new FormItReCaptchaResponse();
        $response->is_valid = false;
        $response->error = $message;
        return $message;
    }

    /**
     * Calls an HTTP POST function to verify if the user's guess was correct
     * @param string $remoteIp
     * @param string $challenge
     * @param string $responseField
     * @param array $extraParams An array of extra variables to post to the server
     * @return FormItReCaptchaResponse
     */
    public function checkAnswer ($remoteIp, $challenge, $responseField, $extraParams = array()) {
        if (empty($this->config[FormItReCaptcha::OPT_PRIVATE_KEY])) {
            return $this->error($this->modx->lexicon('recaptcha.no_api_key'));
        }

        if (empty($remoteIp)) {
            return $this->error($this->modx->lexicon('recaptcha.no_remote_ip'));
        }

        //discard spam submissions
        if (empty($challenge) || empty($responseField)) {
            return $this->error($this->modx->lexicon('recaptcha.empty_answer'));
        }

        $response = $this->httpPost(FormItReCaptcha::VERIFY_SERVER,"/recaptcha/api/verify",array (
            'remoteip' => $remoteIp,
            'challenge' => $challenge,
            'response' => $responseField,
        ) + $extraParams);

        $answers = explode("\n",$response[1]);
        $response = new FormItReCaptchaResponse();

        if (trim($answers[0]) == 'true') {
            $response->is_valid = true;
        } else {
            $response->is_valid = false;
            $response->error = $answers [1];
        }
        return $response;
    }

    /**
     * Gets a URL where the user can sign up for reCAPTCHA. If your application
     * has a configuration page where you enter a key, you should provide a link
     * using this function.
     *
     * @param string $domain The domain where the page is hosted
     * @param string $appname The name of your application
     * @return string
     */
    public function getSignupUrl($domain = null,$appname = null) {
        return "http://recaptcha.net/api/getkey?" .  $this->qsencode(array ('domain' => $domain, 'app' => $appname));
    }

    protected function aesPad($val) {
        $block_size = 16;
        $numpad = $block_size - (strlen ($val) % $block_size);
        return str_pad($val, strlen ($val) + $numpad, chr($numpad));
    }

    /* Mailhide related code */
    protected function aesEncrypt($val,$ky) {
        if (!function_exists("mcrypt_encrypt")) {
            return $this->error($this->modx->lexicon('recaptcha.mailhide_no_mcrypt'));
        }
        $mode=MCRYPT_MODE_CBC;
        $enc=MCRYPT_RIJNDAEL_128;
        $val= $this->aesPad($val);
        return mcrypt_encrypt($enc, $ky, $val, $mode, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
    }


    protected function mailhideUrlbase64($x) {
        return strtr(base64_encode ($x), '+/', '-_');
    }

    /* gets the reCAPTCHA Mailhide url for a given email, public key and private key */
    public function mailhideUrl($email) {
        if (empty($this->config[FormItReCaptcha::OPT_PUBLIC_KEY]) || empty($this->config[FormItReCaptcha::OPT_PRIVATE_KEY])) {
            return $this->error($this->modx->lexicon('recaptcha.mailhide_no_api_key'));
        }

        $ky = pack('H*',$this->config[FormItReCaptcha::OPT_PRIVATE_KEY]);
        $cryptmail = $this->aesEncrypt($email, $ky);
        return 'http://mailhide.recaptcha.net/d?k='
            . $this->config[FormItReCaptcha::OPT_PUBLIC_KEY]
            . '&c=' . $this->mailhideUrlbase64($cryptmail);
    }

    /**
     * Gets the parts of the email to expose to the user.
     * eg, given johndoe@example,com return ["john", "example.com"].
     * the email is then displayed as john...@example.com
     *
     * @param string $email
     * @return array|string
     */
    public function mailhideEmailParts ($email) {
        $arr = preg_split("/@/", $email);

        if (strlen($arr[0]) <= 4) {
            $arr[0] = substr($arr[0], 0, 1);
        } else if (strlen ($arr[0]) <= 6) {
            $arr[0] = substr($arr[0], 0, 3);
        } else {
            $arr[0] = substr($arr[0], 0, 4);
        }
        return $arr;
    }

    /**
     * Gets html to display an email address given a public an private key.
     * to get a key, go to:
     *
     * http://mailhide.recaptcha.net/apikey
     *
     * @param $email
     * @return string
     */
    public function mailhideHtml($email) {
        $emailparts = $this->mailhideEmailParts($email);
        $url = $this->mailhideUrl($email);

        $str = htmlentities($emailparts[0]) . "<a href='" . htmlentities ($url) .
            "' onclick=\"window.open('" . htmlentities ($url) . "', '', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=300'); return false;\" title=\"Reveal this e-mail address\">...</a>@" . htmlentities($emailparts [1]);
        return $str;
    }
}

/**
 * A reCaptchaResponse is returned from reCaptcha::check_answer()
 *
 * @package formit
 * @subpackage recaptcha
 */
class FormItReCaptchaResponse {
    /** @var boolean $is_valid */
    public $is_valid;
    /** @var string $error */
    public $error;
}
}