<?php

namespace Sterc\FormIt;

/**
 * Class Dictionary
 *
 * @package Sterc\FormIt
 */
class Dictionary
{
    /**
     * A reference to the modX instance
     * @var \modX $modx
     */
    public $modx;

    /**
     * A reference to the FormIt instance
     * @var \Sterc\FormIt $formit
     */
    public $formit;

    /**
     * A configuration array
     * @var array $config
     */
    public $config = [];

    /**
     * An array of key->name pairs storing the fields passed
     * @var array $fields
     */
    public $fields = [];

    /**
     * @param \Sterc\FormIt $formit
     * @param array $config
     */
    function __construct($formit, $config = [])
    {
        $this->formit = $formit;
        $this->modx = $formit->modx;
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Get the fields from POST
     *
     * @param array $fields A default set of fields to load
     */
    public function gather($fields = [])
    {
        if (empty($fields)) {
            $fields = [];
        }

        $this->fields = array_merge($fields, $_POST);

        /* Check for files and save to tmp folder */
        if (!empty($_FILES)) {
            /* Only save files if these properties are true */
            if (
                $this->modx->getOption('allowFiles', $this->config, true) &&
                $this->modx->getOption('saveTmpFiles', $this->config, false)
            ) {
                foreach ($_FILES as $key => $value) {
                    if (is_array($value['name'])) {
                        foreach ($value['name'] as $fKey => $fValue) {
                            $this->saveFile(
                                $key . '_' . $fKey,
                                $value['name'][$fKey],
                                $value['tmp_name'][$fKey],
                                $value['error'][$fKey]
                            );
                        }
                    } else {
                        $this->saveFile(
                            $key,
                            $value['name'],
                            $value['tmp_name'],
                            $value['error']
                        );
                    }

                }
            }

            $this->fields = array_merge($this->fields, $_FILES);
        }
    }

    /**
     * Save file.
     *
     * @param string $key
     * @param string $name
     * @param string $tmp_name
     * @param int $error
     */
    public function saveFile($key, $name, $tmp_name, $error)
    {
        $info = pathinfo($name);
        $ext = $info['extension'];
        $ext = strtolower($ext);

        if ($error !== 0) {
            return;
        }

        $allowedFileTypes = array_merge(
            explode(',', $this->modx->getOption('upload_images')),
            explode(',', $this->modx->getOption('upload_media')),
            explode(',', $this->modx->getOption('upload_flash')),
            explode(',', $this->modx->getOption('upload_files', null, ''))
        );
        $allowedFileTypes = array_unique($allowedFileTypes);

        /* Make sure that dangerous file types are not allowed */
        unset(
            $allowedFileTypes['php'],
            $allowedFileTypes['php4'],
            $allowedFileTypes['php5'],
            $allowedFileTypes['htm'],
            $allowedFileTypes['html'],
            $allowedFileTypes['phtml'],
            $allowedFileTypes['js'],
            $allowedFileTypes['bin'],
            $allowedFileTypes['csh'],
            $allowedFileTypes['out'],
            $allowedFileTypes['run'],
            $allowedFileTypes['sh'],
            $allowedFileTypes['htaccess']
        );

        /* Check file extension */
        if (empty($ext) || !in_array($ext, $allowedFileTypes)) {
            return;
        }

        /* Check filesize */
        $maxFileSize = $this->modx->getOption('upload_maxsize', null, 1048576);
        $size = filesize($tmp_name);
        if ($size > $maxFileSize) {
            return;
        }

        $basePath = $this->formit->config['assetsPath'].'tmp/';
        if (!is_dir($basePath)) {
            mkdir($basePath);
        }

        $tmpFileName = md5(session_id().$key.mt_rand(100, 999)).'-'.$info['basename'];
        $target = $basePath.$tmpFileName;

        move_uploaded_file($tmp_name, $target);

        $_FILES[$key]['tmp_name'] = $target;
        $_SESSION['formit']['tmp_files'][] = $target;
    }

    /**
     * Set a value
     *
     * @param string $field
     * @param mixed $value
     */
    public function set($field, $value)
    {
        $this->fields[$field] = $value;
    }

    /**
     * Get a field value
     *
     * @param string $field
     *
     * @return mixed
     */
    public function get($field)
    {
        return $this->fields[$field];
    }

    /**
     * Return all field values in an array of key->name pairs
     *
     * @return array
     */
    public function toArray()
    {
        return $this->fields;
    }

    /**
     * Set a variable number of fields by passing in a key->name pair array
     *
     * @param array $data
     */
    public function fromArray($data)
    {
        foreach ($data as $key => $value) {
            $this->fields[$key] = $value;
        }
    }

    /**
     * Remove a field from the stack
     *
     * @param string $key
     */
    public function remove($key)
    {
        unset($this->fields[$key]);
    }

    /**
     * Stash the fields into the cache
     */
    public function store()
    {
        /* default to store data for 5 minutes */
        $storeTime = $this->modx->getOption('storeTime', $this->config, 300);
        $data = $this->toArray();

        if ($this->modx->getOption('storeLocation', $this->config, 'cache') == 'session') {
            /* store it in the session */
            $_SESSION['formitStore'] = array(
                /* default to store data for 5 minutes */
                'valid' => time() + $storeTime,
                'data' => $data
            );
        } else {
            /* create the hash to store it in the MODX cache */
            $cacheKey = $this->formit->getStoreKey();
            $this->modx->cacheManager->set($cacheKey, $data, $storeTime);
        }

        unset($data);
    }

    /**
     * Retrieve the fields from the cache
     *
     * @return mixed
     */
    public function retrieve()
    {
        if ($this->modx->getOption('storeLocation', $this->config, 'cache') == 'session') {
            if (isset($_SESSION['formitStore']) && time() <= $_SESSION['formitStore']['valid']) {
                return $_SESSION['formitStore']['data'];
            }

            return false;
        }

        $cacheKey = $this->formit->getStoreKey();

        return $this->modx->cacheManager->get($cacheKey);
    }

    /**
     * Erase the stored fields
     *
     * @return bool
     */
    public function erase()
    {
        if ($this->modx->getOption('storeLocation', $this->config, 'cache') == 'session') {
            if (isset($_SESSION['formitStore'])) {
                unset($_SESSION['formitStore']);
            }

            return true;
        }

        $cacheKey = $this->formit->getStoreKey();

        return $this->modx->cacheManager->delete($cacheKey);
    }

    /**
     * Reset fields.
     */
    public function reset()
    {
        $this->fields = [];
    }
}
