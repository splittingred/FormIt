<?php
/**
 * FormIt
 *
 * Copyright 2009-2010 by Shaun McCormick <shaun@modx.com>
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
 * @package formit
 */
class FormIt {
    /**
     * @var int $debugTimer In debug mode, will monitor execution time.
     * @access public
     */
    public $debugTimer = 0;
    /**
     * @var boolean $_initialized True if the class has been initialized or not.
     */
    private $_initialized = false;

    /**
     * FormIt constructor
     *
     * @param modX &$modx A reference to the modX instance.
     * @param array $config An array of configuration options. Optional.
     */
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        /* allows you to set paths in different environments
         * this allows for easier SVN management of files
         */
        $corePath = $this->modx->getOption('formit.core_path',null,MODX_CORE_PATH.'components/formit/');
        $assetsPath = $this->modx->getOption('formit.assets_path',null,MODX_ASSETS_PATH.'components/formit/');
        $assetsUrl = $this->modx->getOption('formit.assets_url',null,MODX_ASSETS_URL.'components/formit/');

        /* loads some default paths for easier management */
        $this->config = array_merge(array(
            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'chunksPath' => $corePath.'elements/chunks/',
            'snippetsPath' => $corePath.'elements/snippets/',
            'controllersPath' => $corePath.'controllers/',

            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl.'css/',
            'jsUrl' => $assetsUrl.'js/',

            'debug' => $this->modx->getOption('formit.debug',null,false),
            'use_multibyte' => (boolean)$this->modx->getOption('use_multibyte',null,false),
            'encoding' => $this->modx->getOption('modx_charset',null,'UTF-8'),
        ),$config);

        $this->modx->addPackage('formit',$this->config['modelPath']);
        if ($this->modx->getOption('formit.debug',$this->config,true)) {
            $this->startDebugTimer();
        }
    }

    /**
     * Initialize the component into a context and provide context-specific
     * handling actions.
     *
     * @access public
     * @return mixed
     */
    public function initialize($context = 'web') {
        switch ($context) {
            case 'mgr': break;
            case 'web':
            default:
                $this->modx->lexicon->load('formit:default');
                $this->_initialized = true;
                break;
        }
        return $this->_initialized;
    }

    /**
     * Loads the Validator class.
     *
     * @access public
     * @param $config array An array of configuration parameters for the
     * validator class
     * @return fiValidator An instance of the fiValidator class.
     */
    public function loadValidator($config = array()) {
        if (!$this->modx->loadClass('formit.fiValidator',$this->config['modelPath'],true,true)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] Could not load Validator class.');
            return false;
        }
        $this->validator = new fiValidator($this,$config);
        return $this->validator;
    }


    /**
     * Loads the Hooks class.
     *
     * @access public
     * @param $type The type of hook to load.
     * @param $config array An array of configuration parameters for the
     * hooks class
     * @return fiHooks An instance of the fiHooks class.
     */
    public function loadHooks($type = 'post',$config = array()) {
        if (!$this->modx->loadClass('formit.fiHooks',$this->config['modelPath'],true,true)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[FormIt] Could not load Hooks class.');
            return false;
        }
        $type = $type.'Hooks';
        $this->$type = new fiHooks($this,$config);
        return $this->$type;
    }

    /**
     * Gets a unique session-based store key for storing form submissions.
     *
     * @return string
     */
    public function getStoreKey() {
        return $this->modx->context->get('key').'/elements/formit/submission/'.md5(session_id());
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
    public function getChunk($name,$properties = array()) {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            if (!$this->config['debug']) {
                $chunk = $this->modx->getObject('modChunk',array('name' => $name),true);
            }
            if (empty($chunk)) {
                $chunk = $this->_getTplChunk($name);
                if ($chunk == false) return false;
            }
            $this->chunks[$name] = $chunk->getContent();
        } else {
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }
    /**
     * Returns a modChunk object from a template file.
     *
     * @access private
     * @param string $name The name of the Chunk. Will parse to name.chunk.tpl
     * @return modChunk/boolean Returns the modChunk object if found, otherwise
     * false.
     */
    private function _getTplChunk($name) {
        $chunk = false;
        $lname = $this->config['use_multibyte'] ? mb_strtolower($name,$this->config['encoding']) : strtolower($name);
        $f = $this->config['chunksPath'].$lname.'.chunk.tpl';
        if (file_exists($f)) {
            $o = file_get_contents($f);
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name',$name);
            $chunk->setContent($o);
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
     * @access public
     * @param string $output The output to process
     * @return string The final wrapped output
     */
    public function output($output) {
        if ($this->debugTimer !== false) {
            $output .= "<br />\nExecution time: ".$this->endDebugTimer()."\n";
        }
        return $output;
    }

    /**
     * Starts the debug timer.
     *
     * @access protected
     * @return int The start time.
     */
    protected function startDebugTimer() {
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
    protected function endDebugTimer() {
        $mtime= microtime();
        $mtime= explode(" ", $mtime);
        $mtime= $mtime[1] + $mtime[0];
        $tend= $mtime;
        $totalTime= ($tend - $this->debugTimer);
        $totalTime= sprintf("%2.4f s", $totalTime);
        $this->debugTimer = false;
        return $totalTime;
    }
}