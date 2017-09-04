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
 * Abstract class for all FormIt modules
 * 
 * @package formit
 * @subpackage modules
 */
abstract class fiModule {
    /** @var modX $modx */
    public $modx;
    /** @var FormIt $formit */
    public $formit;
    /** @var array $config */
    public $config = array();

    /**
     * @param FormIt $formit
     * @param array $config
     */
    function __construct(FormIt $formit,array $config = array()) {
        $this->formit =& $formit;
        $this->modx = $formit->modx;
        $this->config = array_merge($this->config,$config);
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