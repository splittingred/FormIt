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
require_once dirname(__FILE__).'/fimodule.class.php';
/**
 * Loads a option list of states
 * 
 * @package formit
 * @subpackage modules
 */
class fiStateOptions extends fiModule {
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
