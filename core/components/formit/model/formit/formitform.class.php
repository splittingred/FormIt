<?php
/**
 * @package formit
 */
class FormItForm extends xPDOSimpleObject
{
    public function encrypt($value)
    {
        $encryptkey = $this->encryptkey();
        $value = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($encryptkey), $value, MCRYPT_MODE_CBC, md5(md5($encryptkey))));
        return $value;
    }
    public function decrypt($value)
    {
        $encryptkey = $this->encryptkey();
        $values = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($encryptkey), base64_decode($value), MCRYPT_MODE_CBC, md5(md5($encryptkey))), "\0");
        return $values;
    }
    public function generatePseudoRandomHash($bytes = 16)
    {
        $hash = bin2hex(openssl_random_pseudo_bytes($bytes, $strong));
        if (!$strong) {
            $hash = $this->generatePseudoRandomHash($bytes);
        }
        return $hash;
    }

    public function encryptkey()
    {
        $encryptkey = $this->xpdo->getOption('formit.form_encryptkey', null, null, false);
        if (!$encryptkey) {
            $encryptkey = $this->xpdo->site_id;
            $setting = $this->xpdo->getObject('modSystemSetting', array('key' => 'formit.form_encryptkey', 'namespace' => 'formit'));
            if (!$setting) {
                $setting = $this->xpdo->newObject('modSystemSetting');
                $setting->set('key', 'formit.form_encryptkey');
                $setting->set('namespace', 'formit');
            }
            $setting->set('value', $encryptkey);
            $setting->save();
        }
        return $encryptkey;
    }
}
