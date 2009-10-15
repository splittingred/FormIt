<?php
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
     * Base constructor
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
            case 'mgr':
                /* if in mgr, load the manager request class
                 * this handles sub-action page loading
                 * via the 'action' request variable
                 */
                if (!$this->modx->loadClass('formit.request.fiControllerRequest',$this->config['modelPath'],true,true)) {
                    return 'Could not load controller request handler.';
                }
                $this->request = new FiControllerRequest($this);
                return $this->request->handleRequest();
                break;
            case 'web':
            default:
                $this->modx->lexicon->load('formit:web');
                $this->modx->regClientCSS($this->config['cssUrl'].'index.css');
                /* do other front-end related stuff here
                 * that you want to execute in all front-end snippets
                 */
                break;
        }
        return true;
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
        $f = $this->config['chunksPath'].strtolower($name).'.chunk.tpl';
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
        return $this->getChunk('fiWrapper',array(
            'output' => $output,
        ));
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