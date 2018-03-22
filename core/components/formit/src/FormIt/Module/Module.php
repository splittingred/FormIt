<?php

namespace Sterc\FormIt\Module;

abstract class Module
{
    /** @var modX $modx */
    public $modx;
    /** @var FormIt $formit */
    public $formit;
    /** @var array $config */
    public $config = array();

    /**
     * @param \Sterc\FormIt $formit
     * @param array $config
     */
    public function __construct($formit, array $config = array())
    {
        $this->formit =& $formit;
        $this->modx = $formit->modx;
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Runs after instantiation of the module
     * @abstract
     * @return void
     */
    abstract public function initialize();
    /**
     * Returns the output of the module
     * @abstract
     * @return void
     */
    abstract public function output();

    /**
     * Set the default options for this module
     * @param array $defaults
     * @return void
     */
    protected function setDefaultOptions(array $defaults = array()) {
        $this->config = array_merge($defaults,$this->config);
    }

    /**
     * Set an option for this module
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setOption($key,$value) {
        $this->config[$key] = $value;
    }
    /**
     * Set an array of options
     * @param array $array
     * @return void
     */
    public function setOptions($array) {
        foreach ($array as $k => $v) {
            $this->setOption($k,$v);
        }
    }

    /**
     * @param string $key
     * @param mixed $default
     * @param string $method
     * @return null
     */
    public function getOption($key,$default = null,$method = '!empty') {
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
}
