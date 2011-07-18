<?php
/**
 * @package formit
 */
/**
 * Stores values of posted fields
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
        $storeTime = $this->modx->getOption('storeTime',$this->config,300);
        /* create the hash to store */
        $cacheKey = $this->formit->getStoreKey();
        $this->modx->cacheManager->set($cacheKey,$this->toArray(),$storeTime);
    }
}