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
 * Default properties for FormItCountryOptions snippet
 *
 * @package formit
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'selected',
        'desc' => 'prop_fico.selected_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'tpl',
        'desc' => 'prop_fico.tpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'fiDefaultOptionTpl',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'useIsoCode',
        'desc' => 'prop_fico.useisocode_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'limited',
        'desc' => 'prop_fico.limited_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'prioritized',
        'desc' => 'prop_fico.prioritized_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'optGroupTpl',
        'desc' => 'prop_fico.optgrouptpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'fiDefaultOptGroupTpl',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'prioritizedGroupText',
        'desc' => 'prop_fico.prioritizedgrouptext_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'allGroupText',
        'desc' => 'prop_fico.allgrouptext_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'selectedAttribute',
        'desc' => 'prop_fico.selectedattribute_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => ' selected="selected"',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'toPlaceholder',
        'desc' => 'prop_fico.toplaceholder_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'country',
        'desc' => 'prop_fico.country_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '[[++cultureKey]]',
        'lexicon' => 'formit:properties',
    ),
);

return $properties;