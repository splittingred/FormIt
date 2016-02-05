<?php
/**
 * @package formit
 */
class FormItForm extends xPDOSimpleObject {
	public function encrypt($value){
		$encryptkey = $this->xpdo->site_id;
		$value = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($encryptkey), $value, MCRYPT_MODE_CBC, md5(md5($encryptkey))));
		return $value;
	}
	public function decrypt(){
		$encryptkey = $this->xpdo->site_id;
		$values = $this->get('values');
		$values = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($encryptkey), base64_decode($values), MCRYPT_MODE_CBC, md5(md5($encryptkey))), "\0");        
		$values = $this->xpdo->fromJSON($values);
		return $values;
	}
}
?>