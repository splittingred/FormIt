<?php

namespace Sterc;

use Sterc\FormIt\Hook;
use Sterc\FormIt\Module\Module;
use Sterc\FormIt\Request;

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

class FormIt
{
    /**
     * @access public.
     * @var \modX.
     */
    public $modx;

    /**
     * @access public.
     * @var Array.
     */
    public $config = [];

    /**
     * In debug mode, will monitor execution time.
     * @var int $debugTimer
     */
    public $debugTimer = 0;

    /**
     * True if the class has been initialized or not.
     * @var bool $_initialized
     */
    private $_initialized = false;

    /**
     * The fiHooks instance for processing preHooks
     * @var Hook $preHooks
     */
    public $preHooks;

    /**
     * The fiHooks instance for processing postHooks
     * @var Hook $postHooks
     */
    public $postHooks;

    /**
     * The request handling class
     * @var Request $request
     */
    public $request;

    /**
     * An array of cached chunk templates for processing
     * @var array $chunks
     */
    public $chunks = [];

    /**
     * Used when running unit tests to prevent emails/headers from being sent
     * @var bool $inTestMode
     */
    public $inTestMode = false;

    /**
     * A collection of all the errors (from prehooks/posthooks/validator).
     * @var array $errors
     */
    public $errors = [];

    /**
     * Determine if FormIt returns the output, or handle the form normally
     * @var bool $returnOutput
     */
    public $returnOutput = false;

    /**
     * @access public.
     * @param \modX $modx.
     * @param Array $config.
     */
    public function __construct(\modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

        $corePath   = $this->modx->getOption('formit.core_path', $config, $this->modx->getOption('core_path') . 'components/formit/');
        $assetsUrl  = $this->modx->getOption('formit.assets_url', $config, $this->modx->getOption('assets_url') . 'components/formit/');
        $assetsPath = $this->modx->getOption('formit.assets_path', $config, $this->modx->getOption('assets_path') . 'components/formit/');

        $this->config = array_merge([
            'namespace'             => 'formit',
            'lexicons'              => ['formit:default'],
            'base_path'             => $corePath,
            'core_path'             => $corePath,
            'model_path'            => $corePath . 'model/',
            'processors_path'       => $corePath . 'processors/',
            'elements_path'         => $corePath . 'elements/',
            'chunks_path'           => $corePath . 'elements/chunks/',
            'plugins_path'          => $corePath . 'elements/plugins/',
            'snippets_path'         => $corePath . 'elements/snippets/',
            'templates_path'        => $corePath . 'templates/',
            'includes_path'         => $corePath . 'includes/',
            'assets_path'           => $assetsPath,
            'js_url'                => $assetsUrl . 'js/',
            'css_url'               => $assetsUrl . 'css/',
            'assets_url'            => $assetsUrl,
            'connector_url'         => $assetsUrl . 'connector.php',
            'version'               => '4.2.1',
            'placeholderPrefix'     => 'fi.',
            'debug'                 => (bool) $this->modx->getOption('formit.debug', null, false),
            'use_multibyte'         => (bool) $this->modx->getOption('use_multibyte', null, false),
            'encoding'              => $this->modx->getOption('modx_charset', null, 'UTF-8'),
            'max_chars'             => (int) $this->modx->getOption('formit.max_chars_textfield', null, 125),
            'mcrypt'                => function_exists('mcrypt_encrypt'),
            'openssl'               => function_exists('openssl_encrypt'),
            'permissions'           => [
                'encryptions'           => $this->modx->hasPermission('formit_encryptions')
            ]
        ], $config);

        $this->modx->addPackage('formit', $this->config['model_path']);

        if (is_array($this->config['lexicons'])) {
            foreach ($this->config['lexicons'] as $lexicon) {
                $this->modx->lexicon->load($lexicon);
            }
        } else {
            $this->modx->lexicon->load($this->config['lexicons']);
        }

        if ($this->config['debug']) {
            $this->startDebugTimer();
        }
    }

    /**
     * @access public.
     * @return String|Boolean.
     */
    public function getHelpUrl()
    {
        $url = $this->getOption('branding_url_help');

        if (!empty($url)) {
            return $url . '?v=' . $this->config['version'];
        }

        return false;
    }

    /**
     * @access public.
     * @return String|Boolean.
     */
    public function getBrandingUrl()
    {
        $url = $this->getOption('branding_url');

        if (!empty($url)) {
            return $url;
        }

        return false;
    }

    /**
     * Initialize the component into a context and provide context-specific
     * handling actions.
     *
     * @param string $context The context to initialize FormIt into
     *
     * @return mixed
     */
    public function initialize($context = 'web')
    {
        switch ($context) {
            case 'mgr':
                break;
            case 'web':
            default:
                $language = isset($this->config['language']) ? $this->config['language'] . ':' : '';
                $this->modx->lexicon->load($language . 'formit:default');
                $this->_initialized = true;
                break;
        }

        return $this->_initialized;
    }

    /**
     * Sees if the FormIt class has been initialized already
     *
     * @return boolean
     */
    public function isInitialized()
    {
        return $this->_initialized;
    }

    /**
     * Load the fiRequest class
     *
     * @return Request
     */
    public function loadRequest()
    {
        $className = $this->modx->getOption('request_class', $this->config, 'fiRequest');
        $classPath = $this->modx->getOption('request_class_path', $this->config, '');

        if (empty($classPath)) {
            $classPath = $this->config['model_path'].'formit/';
        }

        if ($this->modx->loadClass($className, $classPath, true, true)) {
            $this->request = new \fiRequest($this, $this->config);
        } else {
            $this->modx->log(\modX::LOG_LEVEL_ERROR, '[FormIt] Could not load fiRequest class.');
        }

        return $this->request;
    }

    /**
     * @param string $className
     * @param string $serviceName
     * @param array $config
     *
     * @return Module
     */
    public function loadModule($className, $serviceName, array $config = array())
    {
        if (empty($this->$serviceName)) {
            $classPath = $this->modx->getOption(
                'formit.modules_path',
                null,
                $this->config['model_path'].'formit/module/'
            );

            if ($this->modx->loadClass($className, $classPath, true, true)) {
                $this->$serviceName = new $className($this, $config);
            } else {
                $this->modx->log(
                    \modX::LOG_LEVEL_ERROR,
                    '[FormIt] Could not load module: '.$className.' from '.$classPath
                );
            }
        }
        return $this->$serviceName;
    }

    /**
     * Loads the Hooks class.
     *
     * @access public
     * @param $type string The type of hook to load.
     * @param $config array An array of configuration parameters for the
     * hooks class
     *
     * @return false|Hook An instance of the fiHooks class.
     */
    public function loadHooks($type = 'post', $config = [])
    {
        if (!$this->modx->loadClass('formit.fiHooks', $this->config['model_path'], true, true)) {
            $this->modx->log(\modX::LOG_LEVEL_ERROR, '[FormIt] Could not load Hooks class.');

            return false;
        }

        $typeVar = $type . 'Hooks';
        $this->$typeVar = new \fiHooks($this, $config, $type);

        return $this->$typeVar;
    }

    /**
     * Process the form and return response array
     * Does not execute redirect, but add redirect_url to response
     *
     * @return array
     */
    public function processForm()
    {
        $this->returnOutput = true;
        $this->loadRequest();
        $this->request->prepare();
        $this->request->handle();

        // By default form is successfull
        $response = [
            'success' => true,
            'message' => $this->request->config['successMessage']
        ];

        // Check for errors
        if ($this->hasErrors()) {
            $response['success'] = false;
            $response['error_count'] = count($this->errors);
            $errorMessage = $this->modx->getPlaceholder(
                $this->modx->getOption('placeholderPrefix', $this->request->config, null).'validation_error_message'
            );
            $response['message'] = $errorMessage;
            $response['errors'] = $this->getErrors();
        }

        // Add the form fields to output
        $response['fields'] = $this->request->dictionary->fields;

        // Check for redirect
        if ($this->postHooks && $this->hasHook('redirect')) {
            $url = $this->postHooks->getRedirectUrl();
            $response['redirect_url'] = $url;
        }

        return $response;
    }

    /**
     * Gets a unique session-based store key for storing form submissions.
     *
     * @return string
     */
    public function getStoreKey()
    {
        return $this->modx->context->get('key') . '/elements/formit/submission/' . md5(session_id());
    }

    /**
     * Gets a Chunk and caches it; also falls back to file-based templates
     * for easier debugging.
     *
     * Will always use the file-based chunk if $debug is set to true.
     *
     * @access public
     * @param string $name The name of the Chunk
     * @param array $properties The properties for the Chunk
     * @return string The processed content of the Chunk
     */
    public function getChunk($name, $properties = array())
    {
        if (class_exists('ModxPro\PdoTools\Fetch') && $pdo = $this->modx->getService('pdoTools')) {
            return $pdo->getChunk($name, $properties);
        }

        $chunk = null;

        if (substr($name, 0, 6) === '@CODE:') {
            $content = substr($name, 6);
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($content);
        } elseif (!isset($this->chunks[$name])) {
            if (!$this->config['debug']) {
                $chunk = $this->modx->getObject('modChunk', array('name' => $name), true);
            }

            if (empty($chunk)) {
                $chunk = $this->_getTplChunk($name);
                if ($chunk === false) {
                    return false;
                }
            }

            $this->chunks[$name] = $chunk->getContent();
        } else {
            $content = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($content);
        }

        $chunk->setCacheable(false);

        return $chunk->process($properties);
    }

    /**
     * Returns a modChunk object from a template file.
     *
     * @access private
     * @param string $name The name of the Chunk. Will parse to name.chunk.tpl
     *
     * @return \modChunk|boolean Returns the modChunk object if found, otherwise
     * false.
     */
    public function _getTplChunk($name)
    {
        $chunk = false;
        if (file_exists($name)) {
            $file = $name;
        } else {
            $lowerCaseName = $this->config['use_multibyte'] ? mb_strtolower($name, $this->config['encoding']) : strtolower($name);
            $file = $this->config['chunks_path'] . $lowerCaseName . '.chunk.tpl';
        }

        if (file_exists($file)) {
            $content = file_get_contents($file);
            /** @var \modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name', $name);
            $chunk->setContent($content);
        }

        return $chunk;
    }

    /**
     * Output the final output and wrap in the wrapper chunk. Optional, but
     * recommended for debugging as it outputs the execution time to the output.
     *
     * Also, it is good to output your snippet code with wrappers for easier
     * CSS isolation and styling.
     *
     * @param string $output The output to process
     *
     * @return string The final wrapped output
     */
    public function output($output)
    {
        if ($this->debugTimer !== false) {
            $output .= "<br />\nExecution time: " . $this->endDebugTimer() . "\n";
        }

        return $output;
    }

    /**
     * Starts the debug timer.
     *
     * @return int The start time.
     */
    protected function startDebugTimer()
    {
        $mtime = microtime();
        $mtime = explode(' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $tstart = $mtime;

        $this->debugTimer = $tstart;

        return $this->debugTimer;
    }

    /**
     * Ends the debug timer and returns the total number of seconds script took
     * to run.
     *
     * @access protected
     * @return int The end total time to execute the script.
     */
    protected function endDebugTimer()
    {
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $tend = $mtime;

        $totalTime = ($tend - $this->debugTimer);
        $totalTime = sprintf("%2.4f s", $totalTime);

        $this->debugTimer = false;

        return $totalTime;
    }

    public function setOption($key, $value)
    {
        $this->config[$key] = $value;
    }

    public function setOptions($options)
    {
        foreach ($options as $key => $value) {
            $this->setOption($key, $value);
        }
    }

    public function encryptionMigrationStatus()
    {
        $migrationStatus = true;
        if ($this->modx->getCount('FormItForm', array('encrypted' => 1, 'encryption_type' => 1))) {
            $migrationStatus = false;
        }

        return $migrationStatus;
    }

    /**
     * Check to see if a hook has been passed
     *
     * @param string $hook
     *
     * @return bool
     */
    public function hasHook($hook)
    {
        $hook = $this->getHookName($hook);
        return !!preg_match('#\\b' . preg_quote($hook, '#') . '\\b#i', $this->config['hooks']);
    }

    /**
     * Helper for returning the correct hookname
     * Ensures backwards compatibility with older (<3.0.4) versions
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
     * Helper function for creating hooks
     * This will overwrite all previously set hooks,
     * and set the 'hooks' formit property.
     *
     * @param $hooks array  An array of hooks
     */
    public function setHooks(array $hooks)
    {
        if (is_array($hooks)) {
            $this->setOption('hooks', implode(',', $hooks));
        }
    }

    /**
     * Helper function for creating preHooks
     * This will overwrite all previously set preHooks,
     * and set the 'preHooks' formit property.
     *
     * @param $preHooks array  An array of hooks
     */
    public function setPreHooks(array $preHooks)
    {
        if (is_array($preHooks)) {
            $this->setOption('preHooks', implode(',', $preHooks));
        }
    }

    /**
     * Helper function for setting the 'submitVar' property
     *
     * @param $value string  the submitVar value
     */
    public function setSubmitVar($value)
    {
        $this->setOption('submitVar', $value);
    }

    /**
     * Helper function for setting the 'validationErrorMessage' property
     *
     * @param $value string  the validationErrorMessage value
     */
    public function setValidationErrorMessage($value)
    {
        $this->setOption('validationErrorMessage', $value);
    }

    /**
     * Helper function for setting the 'validationErrorBulkTpl' property
     *
     * @param $value string  the validationErrorBulkTpl value
     */
    public function setValidationErrorBulkTpl($value)
    {
        $this->setOption('validationErrorBulkTpl', $value);
    }

    /**
     * Helper function for setting the 'errTpl' property
     *
     * @param $value string  the errTpl value
     */
    public function setErrTpl($value)
    {
        $this->setOption('errTpl', $value);
    }

    /**
     * Helper function for setting the 'customValidators' property
     *
     * @param $validators array  an array with custom validators
     */
    public function setCustomValidators(array $validators)
    {
        if (is_array($validators)) {
            $this->setOption('customValidators', implode(',', $validators));
        }
    }

    /**
     * Helper function for setting the 'clearFieldsOnSuccess' property
     *
     * @param $value boolean  the clearFieldsOnSuccess value
     */
    public function setClearFieldsOnSuccess($value)
    {
        $this->setOption('clearFieldsOnSuccess', $value);
    }

    /**
     * Helper function for setting the 'store' property
     *
     * @param $value boolean  the store value
     */
    public function setStore($value)
    {
        $this->setOption('store', $value);
    }

    /**
     * Helper function for setting the 'storeTime' property
     *
     * @param $value int  the storeTime value
     */
    public function setStoreTime($value)
    {
        $this->setOption('storeTime', $value);
    }

    /**
     * Helper function for setting the 'storeLocation' property
     *
     * @param $value string  the storeLocation value
     */
    public function setStoreLocation($value)
    {
        $this->setOption('storeLocation', $value);
    }

    /**
     * Helper function for setting the 'placeholderPrefix' property
     *
     * @param $value string  the placeholderPrefix value
     */
    public function setPlaceholderPrefix($value)
    {
        $this->setOption('placeholderPrefix', $value);
    }

    /**
     * Helper function for setting the 'successMessage' property
     *
     * @param $value string  the successMessage value
     */
    public function setSuccessMessage($value)
    {
        $this->setOption('successMessage', $value);
    }

    /**
     * Helper function for setting the 'redirectTo' property
     *
     * @param $value int  the redirectTo value
     */
    public function setRedirectTo($value)
    {
        $this->setOption('redirectTo', $value);
    }

    /**
     * Helper function for setting the 'redirectParams' property
     *
     * @param $params array  the redirectParams array
     */
    public function setRedirectParams(array $params)
    {
        if (is_array($params)) {
            $this->setOption('redirectParams', json_encode($params));
        }
    }

    /**
     * Helper function for setting the 'allowFiles' property
     *
     * @param $value boolean  the allowFiles value
     */
    public function setAllowFiles($value)
    {
        $this->setOption('allowFiles', $value);
    }

    /**
     * Helper function for setting the 'validate' property
     *
     * @param $validate array  the validate array
     */
    public function setValidate(array $validate)
    {
        if (is_array($validate)) {
            $this->setOption('validate', implode(',', $validate));
        }
    }

    /**
     * Helper function for setting the 'emailTpl' property
     *
     * @param $value string  the emailTpl value
     */
    public function setEmailTpl($value)
    {
        $this->setOption('emailTpl', $value);
    }

    /**
     * Helper function for setting the 'emailSubject' property
     *
     * @param $value string  the emailSubject value
     */
    public function setEmailSubject($value)
    {
        $this->setOption('emailSubject', $value);
    }

    /**
     * Helper function for setting the 'emailUseFieldForSubject' property
     *
     * @param $value bool  the emailUseFieldForSubject value
     */
    public function setEmailUseFieldForSubject($value)
    {
        $this->setOption('emailUseFieldForSubject', $value);
    }

    /**
     * Helper function for setting the 'emailTo' property
     *
     * @param $addresses array  An array with emailaddresses
     */
    public function setEmailTo(array $addresses)
    {
        if (is_array($addresses)) {
            $this->setOption('emailTo', implode(',', $addresses));
        }
    }

    /**
     * Helper function for setting the 'emailToName' property
     *
     * @param $names array  An array with email names
     */
    public function setEmailToName(array $names)
    {
        if (is_array($names)) {
            $this->setOption('emailToName', implode(',', $names));
        }
    }

    /**
     * Helper function for setting the 'emailFrom' property
     *
     * @param $value string  the emailFrom value
     */
    public function setEmailFrom($value)
    {
        $this->setOption('emailFrom', $value);
    }

    /**
     * Helper function for setting the 'emailFromName' property
     *
     * @param $value string  the emailFromName value
     */
    public function setEmailFromName($value)
    {
        $this->setOption('emailFromName', $value);
    }

    /**
     * Helper function for setting the 'emailHtml' property
     *
     * @param $value bool  the emailHtml value
     */
    public function setEmailHtml($value)
    {
        $this->setOption('emailHtml', $value);
    }

    /**
     * Helper function for setting the 'emailConvertNewlines' property
     *
     * @param $value bool  the emailConvertNewlines value
     */
    public function setEmailConvertNewlines($value)
    {
        $this->setOption('emailConvertNewlines', $value);
    }

    /**
     * Helper function for setting the 'emailReplyTo' property
     *
     * @param $value string  the emailReplyTo value
     */
    public function setEmailReplyTo($value)
    {
        $this->setOption('emailReplyTo', $value);
    }

    /**
     * Helper function for setting the 'emailReplyToName' property
     *
     * @param $value string  the emailReplyToName value
     */
    public function setEmailReplyToName($value)
    {
        $this->setOption('emailReplyToName', $value);
    }

    /**
     * Helper function for setting the 'emailCC' property
     *
     * @param $addresses array  the emailCC addresses
     */
    public function setEmailCC($addresses)
    {
        if (is_array($addresses)) {
            $this->setOption('emailCC', implode(',', $addresses));
        }
    }

    /**
     * Helper function for setting the 'emailCCName' property
     *
     * @param $names array  the emailCCName email names
     */
    public function setEmailCCName($names)
    {
        if (is_array($names)) {
            $this->setOption('emailCCName', implode(',', $names));
        }
    }

    /**
     * Helper function for setting the 'emailBCC' property
     *
     * @param $addresses array  the emailBCC addresses
     */
    public function setEmailBCC($addresses)
    {
        if (is_array($addresses)) {
            $this->setOption('emailBCC', implode(',', $addresses));
        }
    }

    /**
     * Helper function for setting the 'emailBCCName' property
     *
     * @param $names array  the emailBCCName email names array
     */
    public function setEmailBCCName($names)
    {
        if (is_array($names)) {
            $this->setOption('emailBCCName', implode(',', $names));
        }
    }

    /**
     * Helper function for setting the 'emailMultiWrapper' property
     *
     * @param $value string  the emailMultiWrapper value
     */
    public function setEmailMultiWrapper($value)
    {
        $this->setOption('emailMultiWrapper', $value);
    }

    /**
     * Helper function for setting the 'emailMultiSeparator' property
     *
     * @param $value string  the emailMultiSeparator value
     */
    public function setEmailMultiSeparator($value)
    {
        $this->setOption('emailMultiSeparator', $value);
    }

    /**
     * Helper function for setting the 'fiarTpl' property
     *
     * @param $value string  the fiarTpl value
     */
    public function setFiarTpl($value)
    {
        $this->setOption('fiarTpl', $value);
    }

    /**
     * Helper function for setting the 'fiarSubject' property
     *
     * @param $value string  the fiarSubject value
     */
    public function setFiarSubject($value)
    {
        $this->setOption('fiarSubject', $value);
    }

    /**
     * Helper function for setting the 'fiarToField' property
     *
     * @param $value string  the fiarToField value
     */
    public function setFiarToField($value)
    {
        $this->setOption('fiarToField', $value);
    }

    /**
     * Helper function for setting the 'fiarFrom' property
     *
     * @param $value string  the fiarFrom value
     */
    public function setFiarFrom($value)
    {
        $this->setOption('fiarFrom', $value);
    }

    /**
     * Helper function for setting the 'fiarFromName' property
     *
     * @param $value string  the fiarFromName value
     */
    public function setFiarFromName($value)
    {
        $this->setOption('fiarFromName', $value);
    }

    /**
     * Helper function for setting the 'fiarSender' property
     *
     * @param $value string  the fiarSender value
     */
    public function setFiarSender($value)
    {
        $this->setOption('fiarSender', $value);
    }

    /**
     * Helper function for setting the 'fiarHtml' property
     *
     * @param $value string  the fiarHtml value
     */
    public function setFiarHtml($value)
    {
        $this->setOption('fiarHtml', $value);
    }

    /**
     * Helper function for setting the 'fiarReplyTo' property
     *
     * @param $value string  the fiarReplyTo value
     */
    public function setFiarReplyTo($value)
    {
        $this->setOption('fiarReplyTo', $value);
    }

    /**
     * Helper function for setting the 'fiarReplyToName' property
     *
     * @param $value string  the fiarReplyToName value
     */
    public function setFiarReplyToName($value)
    {
        $this->setOption('fiarReplyToName', $value);
    }

    /**
     * Helper function for setting the 'fiarCC' property
     *
     * @param $value string  the fiarCC value
     */
    public function setFiarCC($value)
    {
        $this->setOption('fiarCC', $value);
    }

    /**
     * Helper function for setting the 'fiarCCName' property
     *
     * @param $value string  the fiarCCName value
     */
    public function setFiarCCName($value)
    {
        $this->setOption('fiarCCName', $value);
    }

    /**
     * Helper function for setting the 'fiarBCC' property
     *
     * @param $value string  the fiarBCC value
     */
    public function setFiarBCC($value)
    {
        $this->setOption('fiarBCC', $value);
    }

    /**
     * Helper function for setting the 'fiarBCCName' property
     *
     * @param $value string  the fiarBCCName value
     */
    public function setFiarBCCName($value)
    {
        $this->setOption('fiarBCCName', $value);
    }

    /**
     * Helper function for setting the 'fiarMultiWrapper' property
     *
     * @param $value string  the fiarMultiWrapper value
     */
    public function setFiarMultiWrapper($value)
    {
        $this->setOption('fiarMultiWrapper', $value);
    }

    /**
     * Helper function for setting the 'fiarMultiSeparator' property
     *
     * @param $value string  the fiarMultiSeparator value
     */
    public function setFiarMultiSeparator($value)
    {
        $this->setOption('fiarMultiSeparator', $value);
    }

    /**
     * Helper function for setting the 'fiarFiles' property
     *
     * @param $value string  the fiarFiles value
     */
    public function setFiarFiles($value)
    {
        $this->setOption('fiarFiles', $value);
    }

    /**
     * Helper function for setting the 'fiarRequired' property
     *
     * @param $value string  the fiarRequired value
     */
    public function setFiarRequired($value)
    {
        $this->setOption('fiarRequired', $value);
    }

    /**
     * Helper function for setting the Saveform 'formName' property
     *
     * @param $value string  the formName value
     */
    public function setFormName($value)
    {
        $this->setOption('formName', $value);
    }

    /**
     * Helper function for setting the 'formEncrypt' property
     *
     * @param $value bool  the formEncrypt value
     */
    public function setFormEncrypt($value)
    {
        $this->setOption('formEncrypt', $value);
    }

    /**
     * Helper function for setting the 'formFields' property
     *
     * @param $fields array  an array of formfields to add to Formsave
     */
    public function setFormFields($fields)
    {
        if (is_array($fields)) {
            $this->setOption('formFields', implode(',', $fields));
        }
    }

    /**
     * Helper function for setting the 'fieldNames' property
     *
     * @param $names array  the fieldNames array
     */
    public function setFieldNames($names)
    {
        if (is_array($names)) {
            $this->setOption('fieldNames', implode(',', $names));
        }
    }


    /**
     * Check for errors in prehooks, posthooks or validator
     */
    public function hasErrors()
    {
        $hasErrors = false;
        if ($this->preHooks && $this->preHooks->hasErrors()) {
            $this->errors['preHooks'][] = $this->preHooks->getErrors();
            $hasErrors = true;
        }

        if ($this->postHooks && $this->postHooks->hasErrors()) {
            $this->errors['hooks'][] = $this->postHooks->getErrors();
            $hasErrors = true;
        }

        if ($this->request->validator && $this->request->validator->hasErrors()) {
            $this->errors['validate'][] = $this->request->validator->getErrors();
            $hasErrors = true;
        }
        return $hasErrors;
    }

    /**
     * Check for errors in prehooks, posthooks or validator
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function __call($name, $arguments)
    {
        // TODO: redirect all the set** methods to some Helper class
    }
}
