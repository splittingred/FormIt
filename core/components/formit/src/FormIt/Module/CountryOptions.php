<?php

namespace Sterc\FormIt\Module;

class CountryOptions extends Module
{
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
            'limited' => '',
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

        /* reduce list to limited countries if option is set */
        $limited = $this->getOption('limited','');
        if (!empty($limited)) {
            $limitedCountries = array();
            $limitedList = explode(',',$limited);
            foreach ($limitedList as $key) {
                $limitedCountries[$key] = $this->countries[$key];
            }
            /* order list by country names */
            asort($limitedCountries, SORT_STRING | SORT_FLAG_CASE);
            $this->countries = $limitedCountries;
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
