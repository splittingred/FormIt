<?php

namespace Sterc\FormIt;

use Sterc\FormIt;

class Validator extends FormIt
{
    /**
     * @var array $errors A collection of all the processed errors so far.
     * @access public
     */
    public $errors = array();
    /**
     * @var array $errorsRaw A collection of all the non-processed errors so far.
     * @access public
     */
    public $errorsRaw = array();
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
     * The constructor for the fiValidator class
     *
     * @param FormIt &$formit A reference to the FormIt class instance.
     * @param array $config Optional. An array of configuration parameters.
     */
    function __construct(FormIt &$formit, array $config = array())
    {
        $this->formit =& $formit;
        $this->modx = $formit->modx;
        $this->config = array_merge(array(
            'placeholderPrefix' => 'fi.',
            'validationErrorBulkTpl' => '<li>[[+error]]</li>',
            'validationErrorBulkSeparator' => "\n",
            'validationErrorBulkFormatJson' => false,
            'validationErrorMessage' => '<p class="error">A form validation error occurred. Please check the values you have entered.</p>',
            'use_multibyte' => (boolean)$this->modx->getOption('use_multibyte',null,false),
            'trimValuesBeforeValidation' => (boolean)$this->modx->getOption('trimValuesBeforeValidation',$this->formit->config,true),
            'encoding' => $this->modx->getOption('modx_charset',null,'UTF-8'),
            'customValidators' => !empty($this->formit->config['customValidators']) ? explode(',',$this->formit->config['customValidators']) : array(),
        ), $config);
    }

    /**
     * Get an option passed in from parameters
     *
     * @param string $key
     * @param mixed $default
     * @param string $method
     * @return null
     */
    public function getOption($key, $default = null, $method = '!empty')
    {
        $v = $default;

        switch ($method) {
            case 'empty':
            case '!empty':
                if (!empty($this->config[$key])) {
                    $v = $this->config[$key];
                }
                break;
            case 'isset':
            default:
                if (isset($this->config[$key])) {
                    $v = $this->config[$key];
                }
                break;
        }
        return $v;
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
     * @param Dictionary $dictionary
     * @param string $validationFields
     * @param string $validationSeparator
     * @return array An array of field name => value pairs.
     */
    public function validateFields(Dictionary $dictionary, $validationFields = '', $validationSeparator = ',')
    {
        $keys = $dictionary->toArray();
        $this->fields = $keys;

        /* process the list of fields that will be validated */
        $validationFields = explode($validationSeparator, $validationFields);
        $fieldValidators = array();
        foreach ($validationFields as $idx => $v) {
            $v = trim(ltrim($v),' '); /* allow multi-line definitions */
            $key = explode(':',$v); /* explode into list separated by : */
            if (!empty($key[0])) {
                $field = $key[0];
                array_splice($key,0,1); /* remove the field name from validator list */
                $fieldValidators[$field] = $key;
                if (!isset($this->fields[$field]) && strpos($field,'.') === false) { /* prevent someone from bypassing a required field by removing it from the form */
                    $keys[$field] = !empty($this->fields[$v]) ? $this->fields[$v] : '';
                }
            }
        }

        /** @var string|array $v */
        foreach ($keys as $k => $v) {
            /* is a array field, ie contact[name] */
            if (is_array($v) && !isset($_FILES[$k]) && is_string($k) && (int)$k === 0 && $k !== 0) {
                $isCheckbox = false;
                foreach ($v as $key => $val) {
                    if (!is_string($key)) {
                        $isCheckbox = true;
                        continue;
                    }
                    $subKey = $k.'.'.$key;
                    $this->_validate($subKey, $val, $fieldValidators);
                }
                if ($isCheckbox) {
                    $this->_validate($k, $v, $fieldValidators);
                }
            } else {
                $this->_validate($k, $v, $fieldValidators);
            }
        }
        /* remove fields that have . in name */
        foreach ($this->fields as $field => $v) {
            if (strpos($field, '.') !== false || strpos($field, ':')) {
                unset($this->fields[$field]);
            }
        }

        /* add fields back into dictionary */
        foreach ($this->fields as $k => $v) {
            $dictionary->set($k, $v);
        }

        return $this->fields;
    }

    /**
     * Helper method for validating fields
     * @param string $k
     * @param string $v
     * @param array $fieldValidators
     * @return void
     */
    private function _validate($k, $v, array $fieldValidators = array())
    {
        $key = explode(':', $k);

        $stripTags = strpos($k, 'allowTags') === false;
        if (isset($fieldValidators[$k])) {
            foreach ($fieldValidators[$k] as $fv) {
                if (strpos($fv, 'allowTags') !== false) {
                    $stripTags = false;
                }
            }
        }

        /* strip tags by default */
        if ($stripTags && !is_array($v)) {
            $v = strip_tags($v);
        }

        $replaceSpecialChars = strpos($k, 'allowSpecialChars') === false;
        if (isset($fieldValidators[$k])) {
            foreach ($fieldValidators[$k] as $fv) {
                if (strpos($fv, 'allowSpecialChars') !== false) {
                    $replaceSpecialChars = false;
                }
            }
        }

        /* htmlspecialchars by default */
        if ($replaceSpecialChars && !is_array($v)) {
            $v = htmlspecialchars($v, ENT_QUOTES, $this->modx->getOption('modx_charset', null, 'UTF-8'));
        }

        /* handle checkboxes/radios with empty hiddens before that are field[] names */
        if (is_array($v) && !isset($_FILES[$key[0]]) && empty($v[0])) {
            array_splice($v, 0, 1);
        }

        /* loop through validators and execute the old way, for backwards compatibility */
        $validators = count($key);
        if ($validators > 1) {
            $this->fields[$key[0]] = $v;
            for ($i=1; $i<$validators; $i++) {
                $this->validate($key[0], $v, $key[$i]);
            }
        } else {
            $this->fields[$k] = $v;
        }

        /* do new way of validation, which is more secure */
        if (!empty($fieldValidators[$k])) {
            foreach ($fieldValidators[$k] as $validator) {
                $this->validate($k, $v, $validator);
            }
        }
    }

    /**
     * Validates a field based on a custom rule, if specified
     *
     * @access public
     * @param string $key The key of the field
     * @param mixed $value The value of the field
     * @param string $type Optional. The type of the validator to apply. Can
     * either be a method name of fiValidator or a Snippet name.
     * @return boolean True if validation was successful. If not, will store
     * error messages to $this->errors.
     */
    public function validate($key, $value, $type = '')
    {
        /** @var boolean|array $validated */
        $validated = false;

        /** @var mixed $value Trim spaces from the value before validating **/
        if (!empty($this->config['trim_values_before_validation'])) {
            $value = trim($value);
        }

        /** @var boolean $hasParams */
        $hasParams = $this->config['use_multibyte'] ? mb_strpos($type,'=',0,$this->config['encoding']) : strpos($type,'=');
        /** @var string|null $param The parameter value, if one is set */
        $param = null;
        if ($hasParams !== false) {
            $len = $this->config['use_multibyte'] ? mb_strlen($type,$this->config['encoding']) : strlen($type);
            $s = $this->config['use_multibyte'] ? mb_substr($type,$hasParams+1,$len,$this->config['encoding']) : substr($type,$hasParams+1,$len);
            $param = str_replace(array('`','^'),'',$s);
            $type = $this->config['use_multibyte'] ? mb_substr($type,0,$hasParams,$this->config['encoding']) : substr($type,0,$hasParams);
        }

        /** @var array $invNames An array of invalid hook names to skip */
        $invNames = array('validate','validateFields','addError','__construct');
        $customValidators = !empty($this->config['customValidators']) ? $this->config['customValidators'] : '';
        $customValidators = explode(',',$customValidators);
        if (method_exists($this,$type) && !in_array($type,$invNames)) {
            /* built-in validator */
            $validated = $this->$type($key,$value,$param);

            /* only allow specified validators to prevent brute force execution of unwanted snippets */
        } else if (in_array($type,$customValidators)) {
            /* attempt to grab custom validator */
            /** @var modSnippet|null $snippet */
            $snippet = $this->modx->getObject('modSnippet',array('name' => $type));
            if ($snippet) {
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
                $this->modx->log(\modX::LOG_LEVEL_ERROR,'[FormIt] Could not find validator "'.$type.'" for field "'.$key.'".');
                $validated = true;
            }
        } else {
            $this->modx->log(\modX::LOG_LEVEL_INFO,'[FormIt] Validator "'.$type.'" for field "'.$key.'" was not specified in the customValidators property.');
            $validated = true;
        }

        /** handle return value errors */
        if (!empty($validated)) {
            if (is_array($validated)) {
                foreach ($validated as $key => $errMsg) {
                    $this->addError($key,$errMsg);
                }
                $validated = false;
            } elseif ($validated !== '1' && $validated !== 1 && $validated !== true) {
                $this->addError($key,$validated);
                $validated = false;
            }
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
    public function addError($key, $value)
    {
        $errTpl = $this->modx->getOption('errTpl', $this->formit->config, '<span class="error">[[+error]]</span>');
        $this->errorsRaw[$key] = $value;
        if (!isset($this->errors[$key])) {
            $this->errors[$key] = '';
        }
        $this->errors[$key] .= ' '.str_replace('[[+error]]', $value, $errTpl);
        return $this->errors[$key];
    }

    /**
     * Check to see if there are any validator errors in the stack
     *
     * @return boolean
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * Get all errors in the stack
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get all raw errors in the stack (errors without the wrapper)
     * @return array
     */
    public function getRawErrors()
    {
        return $this->errorsRaw;
    }

    /**
     * Checks to see if field is required.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @return boolean
     */
    public function required($key, $value)
    {
        $success = false;
        if (is_array($value) && isset($_FILES[$key])) { /* handling file uploads */
            $success = !empty($value['tmp_name']) && isset($value['error']) && $value['error'] == UPLOAD_ERR_OK ? true : false;
        } else {
            $v = (is_array($value)) ? $value : trim($value, ' ');
            $success = (!empty($v) || is_numeric($v)) ? true : false;
        }
        return $success ? true : $this->_getErrorMessage($key, 'vTextRequired', 'formit.field_required', array(
            'field' => $key,
            'value' => is_array($value) ? implode(',', $value) : $value,
        ));
    }

    /**
     * Checks to see if field is blank.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @return boolean
     */
    public function blank($key, $value)
    {
        return empty($value) ? true : $this->_getErrorMessage($key, 'vTextBlank', 'formit.field_not_empty', array(
            'field' => $key,
            'value' => $value,
        ));
    }

    /**
     * Checks to see if passwords match.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @param string $param The parameter passed into the validator that contains the field to check the password against
     * @return boolean
     */
    public function password_confirm($key, $value, $param = 'password_confirm')
    {
        if (empty($value) || $this->fields[$param] != $value) {
            return $this->_getErrorMessage($key, 'vTextPasswordConfirm', 'formit.password_dont_match', array(
                'field' => $key,
                'password' => $value,
                'password_confirm' => $this->fields[$param],
            ));
        }
        return true;
    }

    /**
     * Checks to see if field value is an actual email address.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @return boolean
     */
    public function email($key, $value)
    {
        /* allow empty emails, :required should be used to prevent blank field */
        if (empty($value)) {
            return true;
        }

        /* validate length and @ */
        $pattern = "^[^@]{1,64}\@[^\@]{1,255}$";
        $condition = $this->config['use_multibyte'] ? @mb_ereg($pattern, $value) : @preg_match('/'.$pattern.'/', $value);
        if (!$condition) {
            return $this->_getErrorMessage($key, 'vTextEmailInvalid', 'formit.email_invalid', array(
                'field' => $key,
                'value' => $value,
            ));
        }

        $email_array = explode("@", $value);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            $pattern = "^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$";
            $condition = $this->config['use_multibyte'] ? @mb_ereg($pattern,$local_array[$i]) : @preg_match('/'.$pattern.'/',$local_array[$i]);
            if (!$condition) {
                return $this->_getErrorMessage($key,'vTextEmailInvalid','formit.email_invalid',array(
                    'field' => $key,
                    'value' => $value,
                ));
            }
        }
        /* validate domain */
        $pattern = "^\[?[0-9\.]+\]?$";
        $condition = $this->config['use_multibyte'] ? @mb_ereg($pattern, $email_array[1]) : @preg_match('/'.$pattern.'/', $email_array[1]);
        if (!$condition) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return $this->_getErrorMessage($key,'vTextEmailInvalidDomain','formit.email_invalid_domain',array(
                    'field' => $key,
                    'value' => $value,
                ));
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                $pattern = "^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$";
                $condition = $this->config['use_multibyte'] ? @mb_ereg($pattern,$domain_array[$i]) : @preg_match('/'.$pattern.'/',$domain_array[$i]);
                if (!$condition) {
                    return $this->_getErrorMessage($key,'vTextEmailInvalidDomain','formit.email_invalid_domain',array(
                        'field' => $key,
                        'value' => $value,
                    ));
                }
            }
        }
        return true;
    }

    /**
     * Checks to see if field value is shorter than $param.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @param int $param The minimum length the field can be
     * @return boolean
     */
    public function minLength($key, $value, $param = 0)
    {
        $v = $this->config['use_multibyte'] ? mb_strlen($value,$this->config['encoding']) : strlen($value);
        if ($v < $param) {
            return $this->_getErrorMessage($key,'vTextMinLength','formit.min_length',array(
                'length' => $param,
                'field' => $key,
                'value' => $value,
            ));
        }
        return true;
    }

    /**
     * Checks to see if field value is longer than $param.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @param int $param The maximum length the field can be
     * @return boolean
     */
    public function maxLength($key, $value, $param = 999)
    {
        $v = $this->config['use_multibyte'] ? mb_strlen($value,$this->config['encoding']) : strlen($value);
        if ($v > $param) {
            return $this->_getErrorMessage($key,'vTextMaxLength','formit.max_length',array(
                'length' => $param,
                'field' => $key,
                'value' => $value,
            ));
        }
        return true;
    }

    /**
     * Checks to see if field value is less than $param.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @param int $param The minimum value the field can be
     * @return boolean
     */
    public function minValue($key, $value, $param = 0)
    {
        if ((float)$value < (float)$param) {
            return $this->_getErrorMessage($key,'vTextMinValue','formit.min_value',array(
                'field' => $key,
                'passedValue' => $value,
                'value' => $param,
            ));
        }
        return true;
    }

    /**
     * Checks to see if field value is greater than $param.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @param int $param The maximum value the field can be
     * @return boolean
     */
    public function maxValue($key, $value, $param = 0)
    {
        if ((float)$value > (float)$param) {
            return $this->_getErrorMessage($key,'vTextMaxValue','formit.max_value',array(
                'field' => $key,
                'passedValue' => $value,
                'value' => $param,
            ));
        }
        return true;
    }

    /**
     * See if field contains a certain value.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @param string $expr The regular expression to check against the field
     * @return boolean
     */
    public function contains($key, $value, $expr = '')
    {
        if (!preg_match('/'.$expr.'/i',$value)) {
            return $this->_getErrorMessage($key,'vTextContains','formit.contains',array(
                'field' => $key,
                'passedValue' => $value,
                'value' => $expr,
            ));
        }
        return true;
    }

    /**
     * Strip a string from the value.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @param string $param The value to strip from the field
     */
    public function strip($key, $value, $param = '') {
        $this->fields[$key] = str_replace($param,'',$value);
    }

    /**
     * Strip all tags in the field. The parameter can be a string of allowed
     * tags.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @param string $allowedTags A comma-separated list of tags to allow in the field's value
     * @return boolean
     */
    public function stripTags($key, $value, $allowedTags = '')
    {
        $this->fields[$key] = strip_tags($value,$allowedTags);
        return true;
    }

    /**
     * Strip all tags in the field. The parameter can be a string of allowed
     * tags.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @param string $allowedTags A comma-separated list of tags to allow in the field's value. Leave blank to allow all.
     * @return boolean
     */
    public function allowTags($key, $value, $allowedTags = '') {
        if (empty($allowedTags)) {
            return true;
        }
        $this->fields[$key] = strip_tags($value,$allowedTags);
        return true;
    }

    /**
     * Validates value between a range, specified by min-max.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @param string $ranges The range the value should reside in
     * @return boolean
     */
    public function range($key, $value, $ranges = '0-1')
    {
        $range = explode('-',$ranges);
        if (count($range) < 2) {
            return $this->modx->lexicon('formit.range_invalid');
        }

        if ($value < $range[0] || $value > $range[1]) {
            return $this->_getErrorMessage($key,'vTextRange','formit.range',array(
                'min' => $range[0],
                'max' => $range[1],
                'field' => $key,
                'value' => $value,
                'ranges' => $ranges,
            ));
        }
        return true;
    }

    /**
     * Checks to see if the field is a number.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @return boolean
     */
    public function isNumber($key, $value)
    {
        if (!empty($value) && !is_numeric(trim($value))) {
            return $this->_getErrorMessage($key,'vTextIsNumber','formit.not_number',array(
                'field' => $key,
                'value' => $value,
            ));
        }
        return true;
    }

    /**
     * Checks to see if the field is a valid date. Allows for date formatting as
     * well.
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @param string $format The format of the date
     * @return boolean
     */
    public function isDate($key, $value, $format = '%m/%d/%Y')
    {
        /* allow empty isDate, :required should be used to prevent blank field */
        if (empty($value)) {
            return true;
        }
        $ts = strtotime($value);
        if ($ts === false) {
            return $this->_getErrorMessage($key,'vTextIsDate','formit.not_date',array(
                'format' => $format,
                'field' => $key,
                'value' => $value,
            ));
        }
        if (!empty($format)) {
            $this->fields[$key] = strftime($format,$ts);
        }
        return true;
    }

    /**
     * Checks to see if a string is all lowercase
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @return boolean
     */
    public function islowercase($key, $value)
    {
        $v = $this->config['use_multibyte'] ? mb_strtolower($value,$this->config['encoding']) : strtolower($value);
        return strcmp($v, $value) == 0 ? true : $this->_getErrorMessage($key,'vTextIsLowerCase','formit.not_lowercase',array(
            'field' => $key,
            'value' => $value,
        ));
    }

    /**
     * Checks to see if a string is all uppercase
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @return boolean
     */
    public function isuppercase($key, $value)
    {
        $v = $this->config['use_multibyte'] ? mb_strtoupper($value,$this->config['encoding']) : strtoupper($value);
        return strcmp($v,$value) == 0 ? true : $this->_getErrorMessage($key,'vTextIsUpperCase','formit.not_lowercase',array(
            'field' => $key,
            'value' => $value,
        ));
    }

    /**
     * @param string $key The name of the field
     * @param string $value The value of the field
     * @param string $expression The regexp to use
     * @return boolean
     */
    public function regexp($key, $value, $expression)
    {
        preg_match($expression,$value,$matches);
        return !empty($matches) && !empty($matches[0]) == true ? true : $this->_getErrorMessage($key,'vTextRegexp','formit.not_regexp',array(
            'field' => $key,
            'value' => $value,
            'regexp' => $expression,
        ));
    }

    /**
     * Check for a custom error message, otherwise use a lexicon entry.
     * @param string $field
     * @param string $parameter
     * @param string $lexiconKey
     * @param array $properties
     * @return null|string
     */
    public function _getErrorMessage($field, $parameter, $lexiconKey, array $properties = array())
    {
        if (!empty($this->formit->config[$field.'.'.$parameter])) {
            $message = $this->formit->config[$field.'.'.$parameter];
            $this->modx->lexicon->set($lexiconKey,$message);
            $this->modx->lexicon($lexiconKey,$properties);
        } else if (!empty($this->formit->config[$parameter])) {
            $message = $this->formit->config[$parameter];
            $this->modx->lexicon->set($lexiconKey,$message);
            $this->modx->lexicon($lexiconKey,$properties);
        } else {
            $message = $this->modx->lexicon($lexiconKey,$properties);
        }
        return $message;
    }

    /**
     * Process the errors that have occurred and setup the appropriate placeholders
     * @return void
     */
    public function processErrors()
    {
        $this->modx->toPlaceholders($this->getErrors(),$this->config['placeholderPrefix'].'error');
        $bulkErrTpl = $this->getOption('validationErrorBulkTpl');
        $rawErrs = $this->getRawErrors();
        $errs = array();
        $formatJson = $this->getOption('validationErrorBulkFormatJson');
        if ($formatJson) {
            $errs = '';
            $errs = $this->modx->toJSON($rawErrs);
        } else {
            foreach ($rawErrs as $field => $err) {
                $errs[] = str_replace(array('[[+field]]','[[+error]]'),array($field,$err),$bulkErrTpl);
            }
            $errs = implode($this->getOption('validationErrorBulkSeparator'),$errs);
        }
        $validationErrorMessage = str_replace('[[+errors]]',$errs,$this->getOption('validationErrorMessage'));
        $this->modx->setPlaceholder($this->getOption('placeholderPrefix').'validation_error',true);
        $this->modx->setPlaceholder($this->getOption('placeholderPrefix').'validation_error_message',$validationErrorMessage);
    }

    /**
     * Resets the validator
     * @return void
     */
    public function reset()
    {
        $this->errors = array();
        $this->errorsRaw = array();
    }
}
