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
 * Handles all pre and POST requests for FormIt and abstracts hooks and validation processing.
 *
 * @package formit
 */
class fiRequest {
    /**
     * A reference to the FormIt instance
     * @var FormIt $formit
     */
    public $formit;
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;
    /**
     * An array of configuration properties
     * @var array $config
     */
    public $config = array();
    /**
     * Set to true if wanting to prevent the clearing of fields at the end
     * @var boolean $clearFieldsAtEnd
     */
    public $clearFieldsAtEnd = false;
    /**
     * @var fiValidator $validator
     */
    public $validator;
    /**
     * @var fiDictionary $fiDictionary
     */
    public $dictionary;
    /**
     * @var FormItReCaptcha $reCaptcha
     */
    public $reCaptcha;

    /**
     * @param FormIt &$formit A reference to the FormIt class instance.
     * @param array $config Optional. An array of configuration parameters.
     * @return \fiRequest
     */
    function __construct(FormIt &$formit,array $config = array()) {
        $this->formit =& $formit;
        $this->modx =& $formit->modx;
        $this->config = array_merge(array(
            'clearFieldsOnSuccess' => true,
            'hooks' => '',
            'placeholderPrefix' => 'fi.',
            'preHooks' => '',
            'store' => false,
            'submitVar' => '',
            'validate' => '',
            'validateSeparator' => ',',
        ),$config);
    }

    /**
     * Handle all pre-request data, including loading of preHooks, reCaptcha preparation, and the math hook.
     * @return array An array of pre-fetched fields and their data, possibly set by preHooks
     */
    public function prepare() {
        /* if using recaptcha, load recaptcha html */
        if ($this->hasHook('recaptcha')) {
            $this->loadReCaptcha($this->config);
            if (!empty($this->reCaptcha) && $this->reCaptcha instanceof FormItReCaptcha) {
                $this->reCaptcha->render($this->config);
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] '.$this->modx->lexicon('formit.recaptcha_err_load'));
            }
        }

        /* if using math hook, load default placeholders */
        if ($this->hasHook('math')) {
            if (!$this->hasSubmission()) {
                $mathMaxRange = $this->modx->getOption('mathMaxRange', $this->config, 100);
                $mathMinRange = $this->modx->getOption('mathMinRange', $this->config, 10);
                $op1 = rand($mathMinRange, $mathMaxRange);
                $op2 = rand($mathMinRange, $mathMaxRange);
                /* prevent numbers from being equal */
                while ($op2 == $op1) {
                    $op2 = rand($mathMinRange, $mathMaxRange);
                }
                if ($op2 > $op1) {
                    $t = $op2;
                    $op2 = $op1;
                    $op1 = $t;
                } /* swap so we always get positive #s */
                $operators = array('+', '-');
                $operator = rand(0, 1);
                /* Store in session so math fields are not required for math hook */
                $_SESSION['formitMath'] = array(
                    'op1' => $op1,
                    'op2' => $op2,
                    'operator' => $operators[$operator]
                );
            } else {
                $op1 = $_SESSION['formitMath']['op1'];
                $op2 = $_SESSION['formitMath']['op2'];
                $operators[$operator] = $_SESSION['formitMath']['operator'];
            }

            $this->modx->setPlaceholders(array(
                $this->modx->getOption('mathOp1Field', $this->config, 'op1') => $op1,
                $this->modx->getOption('mathOp2Field', $this->config, 'op2') => $op2,
                $this->modx->getOption('mathOperatorField', $this->config, 'operator') => $operators[$operator],
            ), $this->config['placeholderPrefix']);

        }
        
        return $this->runPreHooks();
    }

    /**
     * Load and run preHooks, setting any fields passed.
     * @return array
     */
    public function runPreHooks() {
        $fields = array();
        $this->formit->loadHooks('pre',$this->config);
        $this->formit->preHooks->loadMultiple($this->config['preHooks'],array(),array(
            'submitVar' => $this->config['submitVar'],
            'hooks' => $this->config['preHooks'],
        ));

        /* if a prehook sets a field, do so here, but only if POST isnt submitted */
        if (!$this->hasSubmission()) {
            $fields = $this->formit->preHooks->gatherFields();
        }

        /* if any errors in preHooks */
        if ($this->formit->preHooks->hasErrors()) {
            $this->formit->preHooks->processErrors();
        }
        return $fields;
    }

    /**
     * Check to see if a hook has been passed
     * @param string $hook
     * @return boolean
     */
    public function hasHook($hook) {
        return strpos($this->config['hooks'],$hook) !== false;
    }

    /**
     * Checks to see if a POST submission for this form has occurred
     * @return boolean
     */
    public function hasSubmission() {
        $inPost = false;
        if (!empty($_POST)) {
            $inPost = true;
            if (!empty($this->config['submitVar']) && empty($_POST[$this->config['submitVar']])) {
                $inPost = false;
            }
        }
        return $inPost;
    }

    /**
     * Load the reCaptcha service class
     *
     * @param array $config An array of configuration parameters for the reCaptcha class
     * @return FormItReCaptcha An instance of the reCaptcha class
     */
    public function loadReCaptcha(array $config = array()) {
        if (empty($this->reCaptcha)) {
            if ($this->modx->loadClass('recaptcha.FormItReCaptcha',$this->config['modelPath'],true,true)) {
                $this->reCaptcha = new FormItReCaptcha($this->formit,$config);
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] '.$this->modx->lexicon('formit.recaptcha_err_load'));
                return null;
            }
        }
        return $this->reCaptcha;
    }

    /**
     * Handle the POST request
     *
     * @param array $fields
     * @return string|void
     */
    public function handle(array $fields = array()) {
        if (!$this->hasSubmission()) return '';
        
        $this->loadDictionary();
        $this->dictionary->gather($fields);

        /* validate fields */
        $this->loadValidator();
        $this->validator->reset();
        $validated = $this->validate($this->config['validate'], $this->config['validateSeparator']);

        if ($validated) {
            $this->postProcess();
        }
        if (!$this->clearFieldsAtEnd) {
            $this->setFieldsAsPlaceholders();
        }
        return '';
    }

    /**
     * Removes files if allowFiles is set to 0
     * @return void
     */
    public function checkForFiles() {
        if (!$this->modx->getOption('allowFiles',$this->config,true)) {
            $fields = $this->dictionary->toArray();
            foreach ($fields as $key => $value) {
                if (is_array($value) && !empty($value['tmp_name'])) {
                    $this->dictionary->remove($key);
                }
            }
        }
    }

    /**
     * Loads the Validator class.
     *
     * @access public
     * @return fiValidator An instance of the fiValidator class.
     */
    public function loadValidator() {
        if ($this->modx->loadClass('formit.fiValidator',$this->formit->config['modelPath'],true,true)) {
            $this->validator = new fiValidator($this->formit,$this->config);
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] Could not load Validator class.');
        }
        return $this->validator;
    }

    /**
     * Load the dictionary storage mechanism
     * @return null|fiDictionary
     */
    public function loadDictionary() {
        if ($this->modx->loadClass('formit.fiDictionary',$this->formit->config['modelPath'],true,true)) {
            $this->dictionary = new fiDictionary($this->formit,$this->config);
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] Could not load Dictionary class.');
        }
        return $this->dictionary;

    }

    /**
     * Validate all fields prior to post processing
     * 
     * @param string $validationString
     * @return bool
     */
    public function validate($validationString, $validationSeparator) {
        $success = true;
        $this->validator->validateFields($this->dictionary,$validationString,$validationSeparator);

        if ($this->validator->hasErrors()) {
            $success = false;
            $this->validator->processErrors();
        }
        return $success;
    }

    /**
     * Handle post-processing through postHooks
     * @return bool
     */
    public function postProcess() {
        $success = $this->runPostHooks();
        if ($success) {
            /* if store is set for FormItRetriever, store fields here */
            $store = $this->modx->getOption('store', $this->config, false);
            if (!empty($store)) {
                $this->dictionary->store();
            }

            /* Remove files older than 1 day uploaded by fiDictionary->gather() */
            $tmpFileLifetime = 86400;
            if ($_SESSION['formit']['tmp_files'] &&
                is_array($_SESSION['formit']['tmp_files']) &&
                count($_SESSION['formit']['tmp_files'])
            ) {
                foreach ($_SESSION['formit']['tmp_files'] as $key => $file) {
                    if (file_exists($file) && (time() - filemtime($file) >= $tmpFileLifetime)) {
                        unlink($file);
                        unset($_SESSION['formit']['tmp_files'][$key]);
                    }
                }
            }
            /* Also do a glob for removing files that are left behind by not-completed form submissions */
            if (function_exists('glob')) {
                $tmpPath = $this->formit->config['assetsPath'].'tmp/';
                foreach (glob($tmpPath.'*') as $file) {
                    if (file_exists($file) && (time() - filemtime($file) >= $tmpFileLifetime)) {
                        unlink($file);
                    }
                }
            }

            /* if the redirect URL was set, redirect */
            $this->checkForRedirect();

            /* set success placeholder */
            $this->setSuccessMessage();

            /* if clearing fields on success, just end here */
            if ($this->modx->getOption('clearFieldsOnSuccess',$this->config,true)) {
                $this->clearFieldsAtEnd = true;
            }
        }

        return $success;
    }

    /**
     * Run any postHooks that were specified.
     *
     * @return boolean True if all hooks executed successfully.
     */
    public function runPostHooks() {
        $success = true;
        /* load posthooks */
        $this->formit->loadHooks('post',$this->config);
        $this->formit->postHooks->loadMultiple($this->config['hooks'],$this->dictionary->toArray());

        /* process form */
        if ($this->formit->preHooks->hasErrors() && $this->modx->getOption('preventPostHooksIfPreHooksErrors',$this->config,true)) {
            /* prevent scripts from running with prehook errors */
            $success = false;
            $this->formit->preHooks->processErrors();
        } elseif ($this->formit->postHooks->hasErrors()) {
            $success = false;
            $this->formit->postHooks->processErrors();
        } else {
            /* assign new values from postHooks */
            $this->dictionary->fromArray($this->formit->postHooks->fields);
        }
        return $success;
    }

    /**
     * Check to see if the redirect URL was set; if so, redirect
     * @return void
     */
    public function checkForRedirect() {
        $url = $this->formit->postHooks->getRedirectUrl();
        if (!empty($url) && !$this->formit->inTestMode) {
            $this->modx->sendRedirect($url);
        }
    }

    /**
     * Sets the success message placeholders
     *
     * @param string $message
     * @return void
     */
    public function setSuccessMessage($message = '') {
        $successMsg = $this->modx->getOption('successMessage',$this->config,$message);

        $this->modx->setPlaceholder($this->config['placeholderPrefix'].'success',true);
        if (!empty($successMsg)) {
            $smPlaceholder = $this->modx->getOption('successMessagePlaceholder',$this->config,$this->config['placeholderPrefix'].'successMessage');
            $this->modx->setPlaceholder($smPlaceholder,$successMsg);
        }
    }

    /**
     * Sets the fields to MODX placeholders
     * @return void
     */
    public function setFieldsAsPlaceholders() {
        $fields = $this->dictionary->toArray();

        /* better handling of checkbox values when input name is an array[] */
        $fs = array();
        /** @var mixed $v */
        foreach ($fields as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $sk => $sv) {
                    $fs[$k.'.'.$sk] = $this->convertMODXTags($sv);
                }
                 $v = $this->modx->toJSON($v);
            }
            /* str_replace to prevent showing of placeholders */
            $fs[$k] = $this->convertMODXTags($v);
        }
        $this->modx->setPlaceholders($fs, $this->config['placeholderPrefix']);
    }

    public function convertMODXTags($v) {
        return str_replace(array('[[',']]'),array('&#91;&#91;','&#93;&#93;'),$v);
    }
}
 
