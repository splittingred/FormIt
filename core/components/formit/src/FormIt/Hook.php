<?php

namespace Sterc\FormIt;

use Sterc\FormIt;
use Sterc\FormIt\Service\Recaptcha;

class Hook
{
    /**
     * A collection of all the processed errors so far.
     * @var array $errors
     */
    public $errors = [];

    /**
     * A collection of all the processed hooks so far.
     * @var array $hooks
     */
    public $hooks = [];

    /**
     * A reference to the modX instance.
     * @var \modX $modx
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
     * If a hook redirects, it needs to set this var to use proper order of execution on redirects/stores
     * @var string
     */
    public $redirectUrl;

    /**
     * The current stored and parsed fields for the FormIt call.
     * @var array $fields
     */
    public $fields = [];

    /**
     * The type of Hook request that this represents
     * @var string $type
     */
    public $type;

    /**
     * The constructor for the fiHooks class
     *
     * @param \Sterc\FormIt $formit A reference to the FormIt class instance.
     * @param array $config Optional. An array of configuration parameters.
     * @param string $type The type of hooks this service class is loading
     */
    public function __construct($formit, $config = [], $type = '')
    {
        $this->formit =& $formit;
        $this->modx = $formit->modx;
        $this->config = array_merge([
            'placeholderPrefix' => 'fi.',
            'errTpl' => '<span class="error">[[+error]]</span>',
            'mathField' => 'math',
            'mathOp1Field' => 'op1',
            'mathOp2Field' => 'op2',
            'mathOperatorField' => 'operator',
            'hookErrorJsonOutputPlaceholder' => ''
        ], $config);

        $this->type = $type;
    }

    /**
     * Loads an array of hooks. If one fails, will not proceed.
     *
     * @param array $hooks The hooks to run.
     * @param array $fields The fields and values of the form
     * @param array $customProperties An array of extra properties to send to the hook
     *
     * @return array An array of field name => value pairs.
     */
    public function loadMultiple($hooks, $fields = [], $customProperties = [])
    {
        if (empty($hooks)) {
            return [];
        }

        if (is_string($hooks)) {
            $hooks = explode(',', $hooks);
        }

        $this->hooks = array();
        $this->fields =& $fields;

        foreach ($hooks as $hook) {
            $hook = trim($hook);
            $success = $this->load($hook, $this->fields, $customProperties);

            if (!$success) {
                return $this->hooks;
            }
            /* dont proceed if hook fails */
        }

        return $this->hooks;
    }

    /**
     * Load a hook. Stores any errors for the hook to $this->errors.
     *
     * @param string $hookName The name of the hook. May be a Snippet name.
     * @param array $fields The fields and values of the form.
     * @param array $customProperties Any other custom properties to load into a custom hook.
     *
     * @return bool True if hook was successful.
     */
    public function load($hookName, $fields = [], $customProperties = [])
    {
        $success = false;
        if (!empty($fields)) {
            $this->fields =& $fields;
        }
        $hookName = $this->getHookName($hookName);
        $this->hooks[] = $hookName;

        $className = 'Sterc\FormIt\Hook\\'.ucfirst($hookName);
        $reserved = array('load','_process','__construct','getErrorMessage');
        if (class_exists($className) && !in_array($hookName, $reserved)) {
            $class = new $className($this, $this->config);
            $success = $class->process($fields);
        } elseif ($snippet = $this->modx->getObject('modSnippet', array('name' => $hookName))) {
            /* custom snippet hook */
            $properties = array_merge($this->formit->config, $customProperties);
            $properties['formit'] =& $this->formit;
            $properties['hook'] =& $this;
            $properties['fields'] = $this->fields;
            $properties['errors'] =& $this->errors;
            $success = $snippet->process($properties);
        } else {
            /* search for a file-based hook */
            $this->modx->parser->processElementTags('', $hookName, true, true);
            if (file_exists($hookName)) {
                $success = $this->_loadFileBasedHook($hookName, $customProperties);
            } else {
                /* no hook found */
                $this->modx->log(\modX::LOG_LEVEL_ERROR, '[FormIt] Could not find hook "'.$hookName.'".');
                $success = true;
            }
        }

        if (is_array($success) && !empty($success)) {
            $this->errors = array_merge($this->errors, $success);
            $success = false;
        } elseif ($success != true) {
            if (!isset($this->errors[$hookName])) {
                $this->errors[$hookName] = '';
            }
            $this->errors[$hookName] .= ' '.$success;
            $success = false;
        }

        return $success;
    }

    /**
     * Attempt to load a file-based hook given a name
     *
     * @param string $path The absolute path of the hook file
     * @param array $customProperties An array of custom properties to run with the hook
     *
     * @return bool True if the hook succeeded
     */
    private function _loadFileBasedHook($path, $customProperties = [])
    {
        $success = false;

        try {
            $success = include $path;
        } catch (\Exception $e) {
            $this->modx->log(\modX::LOG_LEVEL_ERROR,'[FormIt] '.$e->getMessage());
        }

        return $success;
    }

    /**
     * Helper for returning the correct hookname
     *
     * @param string $name The name of the hook
     *
     * @return string The correct name
     */
    public function getHookName($name)
    {
        if ($name === 'FormItAutoResponder') {
            $name = 'autoresponder';
        }
        if ($name === 'FormItSaveForm') {
            $name = 'saveform';
        }
        return $name;
    }

    /**
     * Gets the error messages compiled into a single string.
     *
     * @param string $delim The delimiter between each message.
     *
     * @return string The concatenated error message
     */
    public function getErrorMessage($delim = "\n")
    {
        return implode($delim,$this->errors);
    }

    /**
     * Adds an error to the stack.
     *
     * @param string $key The field to add the error to.
     * @param string $value The error message.
     *
     * @return string The added error message with the error wrapper.
     */
    public function addError($key, $value)
    {
        if (!isset($this->errors[$key])) {
            $this->errors[$key] = '';
        }

        $this->errors[$key] .= $value;

        return $this->errors[$key];
    }

    /**
     * See if there are any errors in the stack.
     *
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * Get all errors for this current request
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Sets the value of a field.
     *
     * @param string $key The field name to set.
     * @param mixed $value The value to set to the field.
     *
     * @return mixed The set value.
     */
    public function setValue($key, $value)
    {
        $this->fields[$key] = $value;

        return $this->fields[$key];
    }

    /**
     * Sets an associative array of field name and values.
     *
     * @param array $values A key/name pair of fields and values to set.
     */
    public function setValues(array $values)
    {
        foreach ($values as $key => $value) {
            $this->setValue($key, $value);
        }
    }

    /**
     * Gets the value of a field.
     *
     * @param string $key The field name to get.
     * @return mixed The value of the key, or null if non-existent.
     */
    public function getValue($key)
    {
        if (array_key_exists($key, $this->fields)) {
            return $this->fields[$key];
        }

        return null;
    }

    /**
     * Gets an associative array of field name and values.
     *
     * @return array $values A key/name pair of fields and values.
     */
    public function getValues()
    {
        return $this->fields;
    }

    /**
     * Processes string and sets placeholders
     *
     * @param string $str The string to process
     * @param array $placeholders An array of placeholders to replace with values
     *
     * @return string The parsed string
     */
    public function _process($str, $placeholders = [])
    {
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
     * Process any errors returned by the hooks and set them to placeholders
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
     *
     * @return array
     */
    public function gatherFields()
    {
        if (empty($this->fields)) {
            return [];
        }

        $fs = $this->getValues();

        /* better handling of checkbox values when input name is an array[] */
        foreach ($fs as $f => $v) {
            if (is_array($v)) { $v = implode(',',$v); }
            $fs[$f] = $v;
        }

        $this->modx->toPlaceholders($fs,$this->config['placeholderPrefix'], '');

        return $this->getValues();
    }
}
