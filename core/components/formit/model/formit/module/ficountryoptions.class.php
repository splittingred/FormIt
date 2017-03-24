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
 * Loads a option list of countries
 * 
 * @package formit
 * @subpackage modules
 */
class fiCountryOptions extends fiModule {
    /** @var array $countries */
    public $countries = array();
    /** @var array $prioritizedCountries */
    public $prioritizedCountries = array();
    /** @var array $list */
    public $list = array();
    /** @var array $prioritizedList */
    public $prioritizedList = array();

    /**
     * @return void
     */
    public function initialize() {
        $this->setDefaultOptions(array(
            'tpl' => 'fiDefaultOptionTpl',
            'selected' => '',
            'useIsoCode' => true,
            'selectedAttribute' => ' selected="selected"',
            'optGroupTpl' => 'fiDefaultOptGroupTpl',
            'prioritized' => '',
            'prioritizedGroupText' => '',
            'allGroupText' => '',
            'outputSeparator' => "\n",
            'toPlaceholder' => '',
            'country' => $this->modx->getOption('cultureKey', array(), 'us', true),
        ));
        $this->setOption('selectedKey',$this->getOption('useIsoCode',true,'isset') ? 'countryKey' : 'countryName');
        $this->modx->lexicon->load('formit:default');
    }

    /**
     * Load the country list
     * @return array
     */
    public function getData() {
        $country = $this->getOption('country','us');
        $countriesFile = $this->getOption('countriesDirectory',$this->formit->config['includesPath']).$country.'.countries.inc.php';
        if (file_exists($countriesFile)) {
            $this->countries = include $countriesFile;
        } else {
            $this->countries = include $this->formit->config['includesPath'].'us.countries.inc.php';
        }
        return $this->countries;
    }

    /**
     * Check for prioritized countries, and load those
     * @return array
     */
    public function loadPrioritized() {
        /* handle prioritized countries */
        $this->prioritizedCountries = array();
        $prioritized = $this->getOption('prioritized','');
        if (!empty($prioritized)) {
            $prioritized = explode(',',$prioritized);
            foreach ($this->countries as $countryKey => $countryName) {
                if (in_array($countryKey,$prioritized)) {
                    $this->prioritizedCountries[] = $countryKey;
                }
            }
        }
        return $this->prioritizedCountries;
    }

    /**
     * iterate over lists
     * @return void
     */
    public function iterate() {
        $this->list = array();
        $this->prioritizedList = array();
        $selected = $this->getOption('selected','');
        $selectedAttribute = $this->getOption('selectedAttribute',' selected="selected"');
        $useIsoCode = $this->getOption('useIsoCode',true,'isset');
        $tpl = $this->getOption('tpl','fiDefaultOptionTpl');
        $selectedKey = $this->getOption('selectedKey','countryKey');
        
        foreach ($this->countries as $countryKey => $countryName) {
            $countryArray = array(
                'text' => $countryName,
                'value' => $useIsoCode ? $countryKey : $countryName,
                'selected' => '',
            );
            if ($selected == $$selectedKey) {
                $countryArray['selected'] = $selectedAttribute;
            }
            $o = $this->formit->getChunk($tpl,$countryArray);
            if (in_array($countryKey,$this->prioritizedCountries)) {
                $this->prioritizedList[] = $o;
            } else {
                $this->list[] = $o;
            }
        }
    }

    /**
     * Handle output generation
     * @return string
     */
    public function output() {
        $outputSeparator = $this->getOption('outputSeparator',"\n");
        if (!empty($this->prioritizedList)) {
            $optGroupTpl = $this->getOption('optGroupTpl','fiDefaultOptGroupTpl');
            $output = $this->formit->getChunk($optGroupTpl,array(
                'text' => $this->getOption('prioritizedGroupText',$this->modx->lexicon('formit.prioritized_group_text')),
                'options' => implode($outputSeparator,$this->prioritizedList),
            ));
            $output .= $this->formit->getChunk($optGroupTpl,array(
                'text' => $this->getOption('allGroupText',$this->modx->lexicon('formit.all_group_text')),
                'options' => implode($outputSeparator,$this->list),
            ));
        } else {
            $output = implode($outputSeparator,$this->list);
        }

        /* set to placeholder or output */
        $toPlaceholder = $this->getOption('toPlaceholder','');
        if (!empty($toPlaceholder)) {
            $this->modx->setPlaceholder($toPlaceholder,$output);
            $output = '';
        }
        return $output;
    }
}
