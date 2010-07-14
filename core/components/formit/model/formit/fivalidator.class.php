<?php
/**
 * FormIt
 *
 * Copyright 2009-2010 by Shaun McCormick <shaun@modxcms.com>
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
 * Handles custom validaton
 *
 * @package formit
 */
class fiValidator {
    /**
     * @var array $errors A collection of all the processed errors so far.
     * @access public
     */
    public $errors = array();
    /**
     * @var array $fields A collection of all the validated fields so far.
     * @access public
     */
    public $fields = array();
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
     * The constructor for the lgnValidator class
     *
     * @param Login &$login A reference to the Login class instance.
     * @param array $config Optional. An array of configuration parameters.
     * @return lgnValidator
     */
    function __construct(FormIt &$formit,array $config = array()) {
        $this->formit =& $formit;
        $this->modx =& $formit->modx;
        $this->config = array_merge(array(
            'use_multibyte' => (boolean)$this->modx->getOption('use_multibyte',null,false),
            'encoding' => $this->modx->getOption('modx_charset',null,'UTF-8'),
        ),$config);
    }

    /**
     * Validates an array of fields. Returns the field names and values, with
     * the field names stripped of their validators.
     *
     * The key names can be in this format:
     *
     * name:validator=param:anotherValidator:oneMoreValidator=`param`
     *
     * @access public
     * @param array $keys The fields to validate.
     * @return array An array of field name => value pairs.
     */
    public function validateFields(array $keys = array()) {
        $this->fields = array();
        foreach ($keys as $k => $v) {
            $key = explode(':',$k);
            
            /* strip tags by default */
            if (strpos($k,'allowTags') === false && !is_array($v)) {
                $v = strip_tags($v);
            }

            $validators = count($key);
            if ($validators > 1) {
                $this->fields[$key[0]] = $v;
                for ($i=1;$i<$validators;$i++) {
                    $this->validate($key[0],$v,$key[$i]);
                }
            } else {
                $this->fields[$k] = $v;
            }
        }
        return $this->fields;
    }

    /**
     * Validates a field based on a custom rule, if specified
     *
     * @access public
     * @param string $key The key of the field
     * @param mixed $value The value of the field
     * @param string $type Optional. The type of the validator to apply. Can
     * either be a method name of lgnValidator or a Snippet name.
     * @return boolean True if validation was successful. If not, will store
     * error messages to $this->errors.
     */
    public function validate($key,$value,$type = '') {
        $validated = false;

        $hasParams = $this->config['use_multibyte'] ? mb_strpos($type,'=',0,$this->config['encoding']) : strpos($type,'=');
        $param = null;
        if ($hasParams !== false) {
            $len = $this->config['use_multibyte'] ? mb_strlen($type,$this->config['encoding']) : strlen($type);
            $s = $this->config['use_multibyte'] ? mb_substr($type,$hasParams+1,$len,$this->config['encoding']) : substr($type,$hasParams+1,$len);
            $param = str_replace('`','',$s);
            $type = $this->config['use_multibyte'] ? mb_substr($type,0,$hasParams,$this->config['encoding']) : substr($type,0,$hasParams);
        }

        $invNames = array('validate','validateFields','_addError','__construct');
        if (method_exists($this,$type) && !in_array($type,$invNames)) {
            /* built-in validator */
            $validated = $this->$type($key,$value,$param);

        } else if ($snippet = $this->modx->getObject('modSnippet',array('name' => $type))) {
            /* custom snippet validator */
            $props = array_merge($this->formit->config,array(
                'key' => $key,
                'value' => $value,
                'param' => $param,
                'type' => $type,
                'validator' => &$this,
                'errors' => &$this->errors,
            ));
            $validated = $snippet->process($props);

        } else {
            /* no validator found */
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] Could not find validator "'.$type.'" for field "'.$key.'".');
            $validated = true;
        }

        if (is_array($validated) && !empty($validated)) {
            foreach ($validated as $key => $errMsg) {
                $this->_addError($key,$errMsg);
            }
            $validated = false;
        } elseif ($validated !== '1' && $validated !== 1 && $validated !== true) {            
            $this->_addError($key,$validated);
            $validated = false;
        }

        return $validated;
    }

    /**
     * Adds an error to the stack.
     *
     * @access private
     * @param string $key The field to add the error to.
     * @param string $value The error message.
     * @return string The added error message with the error wrapper.
     */
    private function _addError($key,$value) {
        $errTpl = $this->modx->getOption('errTpl',$this->formit->config,'<span class="error">[[+error]]</span>');
        $this->errors[$key] .= ' '.str_replace('[[+error]]',$value,$errTpl);
        return $this->errors[$key];
    }

    /**
     * Checks to see if field is required.
     */
    public function required($key,$value) {
        $success = false;
        if (is_array($value)) {
            $success = !empty($value['tmp_name']) && isset($value['error']) && $value['error'] == UPLOAD_ERR_OK ? true : false;
        } else {
            $success = !empty($value) ? true : false;
        }
        return $success ? true : $this->modx->lexicon('formit.field_required');
    }

    /**
     * Checks to see if field is blank.
     */
    public function blank($key,$value) {
        return empty($value) ? true : $this->modx->lexicon('formit.field_not_empty');
    }

    /**
     * Checks to see if passwords match.
     */
    public function password_confirm($key,$value,$param = 'password_confirm') {
        if (empty($value)) return $this->modx->lexicon('register.password_not_confirmed');
        if ($this->fields[$param] != $value) {
            return $this->modx->lexicon('formit.password_dont_match');
        }
        return true;
    }

    /**
     * Checks to see if field value is an actual email address.
     */
    public function email($key,$value) {
        /* validate length and @ */
        $pattern = "^[^@]{1,64}\@[^\@]{1,255}$";
        $condition = $this->config['use_multibyte'] ? @mb_ereg($pattern,$value) : @ereg($pattern, $value);
        if (!$condition) {
            return $this->modx->lexicon('formit.email_invalid');
        }

        $email_array = explode("@", $value);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            $pattern = "^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$";
            $condition = $this->config['use_multibyte'] ? @mb_ereg($pattern,$local_array[$i]) : @ereg($pattern,$local_array[$i]);
            if (!$condition) {
                return $this->modx->lexicon('formit.email_invalid');
            }
        }
        /* validate domain */
        $pattern = "^\[?[0-9\.]+\]?$";
        $condition = $this->config['use_multibyte'] ? @mb_ereg($pattern, $email_array[1]) : @ereg($pattern, $email_array[1]);
        if (!$condition) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return $this->modx->lexicon('formit.email_invalid_domain');
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                $pattern = "^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$";
                $condition = $this->config['use_multibyte'] ? @mb_ereg($pattern,$domain_array[$i]) : @ereg($pattern,$domain_array[$i]);
                if (!$condition) {
                    return $this->modx->lexicon('formit.email_invalid_domain');
                }
            }
        }
        return true;
    }

    /**
     * Checks to see if field value is shorter than $param.
     */
    public function minLength($key,$value,$param = 0) {
        $v = $this->config['use_multibyte'] ? mb_strlen($value,$this->config['encoding']) : strlen($value);
        if ($v < $param) {
            return $this->modx->lexicon('formit.min_length',array('length' => $param));
        }
        return true;
    }

    /**
     * Checks to see if field value is longer than $param.
     */
    public function maxLength($key,$value,$param = 999) {
        $v = $this->config['use_multibyte'] ? mb_strlen($value,$this->config['encoding']) : strlen($value);
        if ($v > $param) {
            return $this->modx->lexicon('formit.max_length',array('length' => $param));
        }
        return true;
    }

    /**
     * Checks to see if field value is less than $param.
     */
    public function minValue($key,$value,$param = 0) {
        if ((float)$value < (float)$param) {
            return $this->modx->lexicon('formit.min_value',array('value' => $param));
        }
        return true;
    }

    /**
     * Checks to see if field value is greater than $param.
     */
    public function maxValue($key,$value,$param = 0) {
        if ((float)$value > (float)$param) {
            return $this->modx->lexicon('formit.max_value',array('value' => $param));
        }
        return true;
    }

    /**
     * See if field contains a certain value.
     */
    public function contains($key,$value,$expr = '') {
        if (!preg_match('/'.$expr.'/i',$value)) {
            return $this->modx->lexicon('formit.contains',array('value' => $expr));
        }
        return true;
    }

    /**
     * Strip a string from the value.
     */
    public function strip($key,$value,$param = '') {
        $this->fields[$key] = str_replace($param,'',$value);
    }

    /**
     * Strip all tags in the field. The parameter can be a string of allowed
     * tags.
     */
    public function stripTags($key,$value,$allowedTags = '') {
        $this->fields[$key] = strip_tags($value,$allowedTags);
        return true;
    }

    /**
     * Strip all tags in the field. The parameter can be a string of allowed
     * tags.
     */
    public function allowTags($key,$value,$allowedTags = '<strong><em><b><i><li><ul><a>') {
        $this->fields[$key] = strip_tags($value,$allowedTags);
        return true;
    }

    /**
     * Validates value between a range, specified by min-max.
     */
    public function range($key,$value,$ranges = '0-1') {
        $range = explode('-',$ranges);
        if (count($range) < 2) return $this->modx->lexicon('formit.range_invalid');

        if ($value < $range[0] || $value > $range[1]) {
            return $this->modx->lexicon('formit.range',array(
                'min' => $range[0],
                'max' => $range[1],
            ));
        }
        return true;
    }

    /**
     * Checks to see if the field is a number.
     */
     public function isNumber($key,$value) {
         if (!is_numeric($value)) {
             return $this->modx->lexicon('formit.not_number');
         }
     }

    /**
     * Checks to see if the field is a valid date. Allows for date formatting as
     * well.
     */
    public function isDate($key,$value,$format = '%m/%d/%Y') {
        $ts = strtotime($value);
        if ($ts === false || empty($value)) {
            return $this->modx->lexicon('formit.not_date',array('format' => $format));
        }
        if (!empty($format)) {
            $this->fields[$key] = strftime($format,$ts);
        }
        return true;
    }

    /**
     * Checks to see if a string is all lowercase
     */
    public function islowercase($key,$value) {
        $v = $this->config['use_multibyte'] ? mb_strtolower($value,$this->config['encoding']) : strtolower($value);
        return strcmp($v,$value) == 0 ? true : $this->modx->lexicon('formit.not_lowercase');
    }

    /**
     * Checks to see if a string is all uppercase
     */
    public function isuppercase($key,$value) {
        $v = $this->config['use_multibyte'] ? mb_strtoupper($value,$this->config['encoding']) : strtoupper($value);
        return strcmp($v,$value) == 0 ? true : $this->modx->lexicon('formit.not_lowercase');
    }
}