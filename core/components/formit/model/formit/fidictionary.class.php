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
 * Abstracts storage of values of posted fields and fields set by hooks.
 *
 * @package formit
 */
class fiDictionary {
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;
    /**
     * A reference to the FormIt instance
     * @var FormIt $formit
     */
    public $formit;
    /**
     * A configuration array
     * @var array $config
     */
    public $config = array();
    /**
     * An array of key->name pairs storing the fields passed
     * @var array $fields
     */
    public $fields = array();

    /**
     * @param FormIt $formit
     * @param array $config
     */
    function __construct(FormIt &$formit,array $config = array()) {
        $this->modx =& $formit->modx;
        $this->formit =& $formit;
        $this->config = array_merge($this->config,$config);
    }

    /**
     * Get the fields from POST
     *
     * @param array $fields A default set of fields to load
     * @return void
     */
    public function gather(array $fields = array()) {
        if (empty($fields)) $fields = array();
        $this->fields = array_merge($fields,$_POST);
        if (!empty($_FILES)) { $this->fields = array_merge($this->fields,$_FILES); }
    }

    /**
     * Set a value
     * @param string $field
     * @param mixed $value
     * @return void
     */
    public function set($field,$value) {
        $this->fields[$field] = $value;
    }

    /**
     * Get a field value
     * @param string $field
     * @return mixed
     */
    public function get($field) {
        return $this->fields[$field];
    }

    /**
     * Return all field values in an array of key->name pairs
     * @return array
     */
    public function toArray() {
        return $this->fields;
    }

    /**
     * Set a variable number of fields by passing in a key->name pair array
     * @param array $array
     * @return void
     */
    public function fromArray(array $array) {
        foreach ($array as $k => $v) {
            $this->fields[$k] = $v;
        }
    }

    /**
     * Remove a field from the stack
     * @param string $key
     * @return void
     */
    public function remove($key) {
        unset($this->fields[$key]);
    }

    /**
     * Stash the fields into the cache
     * 
     * @return void
     */
    public function store() {
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
    public function retrieve() {
        if ($this->modx->getOption('storeLocation', $this->config, 'cache') == 'session') {
            if (isset($_SESSION['formitStore']) && time() <= $_SESSION['formitStore']['valid']) {
                return $_SESSION['formitStore']['data'];
            } else {
                return false;
            }
        } else {
            $cacheKey = $this->formit->getStoreKey();
            return $this->modx->cacheManager->get($cacheKey);
        }
    }

    /**
     * Erase the stored fields
     * 
     * @return boolean
     */
    public function erase() {
        if ($this->modx->getOption('storeLocation', $this->config, 'cache') == 'session') {
            if (isset($_SESSION['formitStore'])) {
                unset($_SESSION['formitStore']);
            }
            return true;
        } else {
            $cacheKey = $this->formit->getStoreKey();
            return $this->modx->cacheManager->delete($cacheKey);
        }
    }

    /**
     * @return void
     */
    public function reset() {
        $this->fields = array();
    }
}