<?php

namespace Sterc\FormIt\Hook;

class Saveform
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
     * Send an autoresponder email of the form.
     *
     * Properties:
     *  formName    The name of the form. Defaults to "form-{resourceid}".
     *  formEncrypt If is set to '1' (true) the submitted form will be encrypted before saving inside the DB.
     *  formFields  A comma-separated list of fields that will be saved.
     *      Defaults will save all fields including the submit button.
     *  fieldNames  Change the name of the field inside the CMP.
     *      So if the field name is email2 you could change the name to "secondary email".
     *      Ex: &fieldnames=`fieldname==newfieldname, anotherone==anothernewname`.
     *
     * @param array $fields An array of cleaned POST fields
     *
     * @return bool True if form was successfully saved
     */
    public function process($fields = [])
    {
        /* setup default properties */
        $values = $this->hook->getValues();
        $identifier = basename($_SERVER['REQUEST_URI']);
        $contextKey = '-';
        if ($this->modx->resource) {
            $identifier = $this->modx->resource->get('id');
            $contextKey = $this->modx->resource->get('context_key');
        }
        $formName = $this->modx->getOption('formName', $this->formit->config, 'form-'.$identifier);
        // process formName. Pick a value from the form
        // Inspired from the email's hook of formit (fihooks.class.php)
        if (is_string($formName)) {
            foreach ($fields as $k => $v) {
                if (is_scalar($k) && is_scalar($v)) {
                    $formName = str_replace('[[+'.$k.']]',$v,$formName);
                }
            }
        }

        $formEncrypt = $this->modx->getOption('formEncrypt', $this->formit->config, false);
        $formFields = $this->modx->getOption('formFields', $this->formit->config, false);
        $fieldNames = $this->modx->getOption('fieldNames', $this->formit->config, false);
        $updateSavedForm = $this->modx->getOption('updateSavedForm', $this->formit->config, false); // true, false, '1', '0', or 'values'
        // In order to use update process, you need to provide the hash key to the user somehow
        // Usually with [[!FormItRetriever]] to populate this form field:
        $formHashKeyField = $this->modx->getOption('savedFormHashKeyField', $this->formit->config, 'savedFormHashKey');
        // Disable if you want to use the session_id() in your hash, like FormIt does
        // WARNING: this can cause potential hash key collisions and overwriting of the wrong form record!!
        $formHashKeyRandom = $this->modx->getOption('formHashKeyRandom', $this->formit->config, true);
        // process formHashKeyField into variable for later use
        $formHashKey = (isset($values[$formHashKeyField])) ? (string) $values[$formHashKeyField] : '';
        // our hashing methods return 32 chars
        if (strlen($formHashKey) !== 32) $formHashKey = '';
        unset($values[$formHashKeyField]);

        if ($formFields) {
            $formFields = explode(',', $formFields);
            foreach($formFields as $k => $v) {
                $formFields[$k] = trim($v);
            }
        }
        // Build the data array
        $dataArray = array();
        if ($formFields) {
            foreach ($formFields as $field) {
                $fieldValue = isset($values[$field]) ? $values[$field] : '';
                // When field is file field, value is an array
                if (is_array($fieldValue) && isset($fieldValue['tmp_name'], $fieldValue['name'])) {
                    $fieldValue = $fieldValue['name'];
                }
                $dataArray[$field] = $fieldValue;
            }
        } else {
            $dataArray = $values;
        }
        // Change the fieldnames
        if ($fieldNames) {
            $newDataArray = array();
            $fieldLabels = array();
            $formFieldNames = explode(',', $fieldNames);
            foreach ($formFieldNames as $formFieldName) {
                $parts = explode('==', $formFieldName);
                $fieldLabels[trim($parts[0])] = trim($parts[1]);
            }
            foreach ($dataArray as $key => $value) {
                if ($fieldLabels[$key]) {
                    $labelKey = $fieldLabels[$key];
                    if (array_key_exists($labelKey, $newDataArray)) {
                        $labelKey .= ' ('.$key.')';
                    }
                    $newDataArray[$labelKey] = $value;
                } else {
                    $newDataArray[$key] = $value;
                }
            }
            $dataArray = $newDataArray;
        }
        // We only enter update mode if we already have a valid formHashKey (tested above)
        // AND the updateSavedForm param was set to a truth-y value.
        $mode = ($updateSavedForm && $formHashKey) ? 'update' : 'create';
        // Create/get obj
        $newForm = null;
        if ($mode === 'update') {
            $newForm = $this->modx->getObject('FormItForm', array('hash' => $formHashKey));
        }
        if ($newForm === null) {
            $newForm = $this->modx->newObject('FormItForm');
        }

        // Array from which to populate form record
        $newFormArray = array();

        // Handle encryption
        $encryptionType = 1;
        if ($formEncrypt) {
            $dataArray = $newForm->encrypt($this->modx->toJSON($dataArray));
            // Only set encryption type if encryption is successful
            if ($dataArray) {
                // Set encryption type to 2 (openssl)
                $encryptionType = 2;
            }
        } else {
            $dataArray = $this->modx->toJSON($dataArray);
        }

        // Create new hash key on create mode, and handle invalid hash keys. 
        if ($mode === 'create') {
            $formHashKey = ($formHashKeyRandom) ? $newForm->generatePseudoRandomHash() : pathinfo($this->formit->getStoreKey(), PATHINFO_BASENAME);
        }

        // Special case: if updateSavedForm has the flag 'values' we only merge in
        // the form values, not the other stuff
        if ($mode === 'update' && $updateSavedForm === 'values') {
            $newFormArray = $newForm->toArray();
            $newFormArray = array_merge($newFormArray, array(
                'values' => $dataArray,
                'encryption_type' => $encryptionType,
            ));
        } else {
            // In all other cases, we overwrite the record completely!
            // In create mode we must save the hash. In update mode, the 
            // formHashKey will be valid so we can also save it, again.
            $newFormArray = array(
                'form' => $formName,
                'date' => time(),
                'values' => $dataArray,
                'ip' => $this->modx->getOption('REMOTE_ADDR', $_SERVER, ''),
                'context_key' => $contextKey,
                'encrypted' => $formEncrypt,
                'encryption_type' => $encryptionType,
                'hash' => $formHashKey,
            );
        }
        // Convert to object
        $newForm->fromArray($newFormArray);
        // Attempt to save
        if (!$newForm->save()) {
            $this->modx->log(\modX::LOG_LEVEL_ERROR, '[FormItSaveForm] An error occurred while trying to save the submitted form: ' . print_r($newForm->toArray(), true));
            return false;
        }
        $storeAttachments = $this->modx->getOption('storeAttachments', $this->config, false);
        if ($storeAttachments) {
            $newForm->storeAttachments($this->formit->config);
        }
        // Pass the hash and form data back to the user
        $this->hook->setValue('savedForm', $newForm->toArray());
        $this->hook->setValue($formHashKeyField, $newForm->get('hash'));
        return true;
    }
}
