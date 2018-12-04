<?php

namespace Sterc\FormIt\Module;

class StateOptions extends Module
{
    /** @var array $states */
    public $states = array();
    /** @var array $list */
    public $list = array();

    /**
     * @return void
     */
    public function initialize() {
        $this->setDefaultOptions(array(
            'tpl' => 'fiDefaultOptionTpl',
            'selected' => '',
            'useAbbr' => true,
            'selectedAttribute' => ' selected="selected"',
            'outputSeparator' => "\n",
            'toPlaceholder' => '',
            'country' => $this->modx->getOption('cultureKey', array(), 'us', true),
        ));
        $this->setOption('selectedKey',$this->getOption('useAbbr',true) ? 'stateKey' : 'stateName');
    }

    /**
     * Load the country list
     * @return array
     */
    public function getData() {
        $country = strtolower( $this->getOption('country','us') );
        $statesFile = $this->getOption('statesDirectory',$this->formit->config['includesPath']).$country.'.states.inc.php';
        if (file_exists($statesFile)) {
            $this->states = include $statesFile;
        } else {
            $this->states = include $this->formit->config['includesPath'].'us.states.inc.php';
        }
        return $this->states;
    }

    /**
     * iterate over lists
     * @return void
     */
    public function iterate() {
        $selected = $this->getOption('selected', '');
        $selectedAttribute = $this->getOption('selectedAttribute', ' selected="selected"');
        $tpl = $this->getOption('tpl', 'fiDefaultOptionTpl');
        $selectedKey = $this->getOption('selectedKey', 'stateKey');
        foreach ($this->states as $stateKey => $stateName) {
            $stateArray = array(
                'text' => $stateName,
                'value' => $$selectedKey,
                'selected' => '',
            );
            if ($selected == $$selectedKey) {
                $stateArray['selected'] = $selectedAttribute;
            }
            $this->list[] = $this->formit->getChunk($tpl, $stateArray);
        }
    }

    /**
     * Handle output generation
     * @return string
     */
    public function output()
    {
        $outputSeparator = $this->getOption('outputSeparator', "\n");
        $output = implode($outputSeparator, $this->list);

        /* set to placeholder or output */
        $toPlaceholder = $this->getOption('toPlaceholder', '');
        if (!empty($toPlaceholder)) {
            $this->modx->setPlaceholder($toPlaceholder, $output);
            $output = '';
        }
        return $output;
    }
}
