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
 * @package formit
 * @subpackage build
 */
$snippets = array();

$snippets[0]= $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'FormIt',
    'description' => 'A dynamic form processing snippet.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.formit.php'),
),'',true,true);
$properties = include $sources['properties'].'properties.formit.php';
$snippets[0]->setProperties($properties);
unset($properties);

$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'FormItAutoResponder',
    'description' => 'Custom hook for FormIt to handle Auto-Response emails.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.formitautoresponder.php'),
),'',true,true);

$snippets[2]= $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 2,
    'name' => 'FormItRetriever',
    'description' => 'Fetches a form submission for a user for displaying on a thank you page.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.formitretriever.php'),
),'',true,true);
$properties = include $sources['properties'].'properties.formitretriever.php';
$snippets[2]->setProperties($properties);
unset($properties);

$snippets[3]= $modx->newObject('modSnippet');
$snippets[3]->fromArray(array(
    'id' => 3,
    'name' => 'FormItIsChecked',
    'description' => 'A custom output filter used with checkboxes/radios for checking checked status.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.formitischecked.php'),
),'',true,true);

$snippets[4]= $modx->newObject('modSnippet');
$snippets[4]->fromArray(array(
    'id' => 4,
    'name' => 'FormItIsSelected',
    'description' => 'A custom output filter used with dropdowns for checking selected status.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.formitisselected.php'),
),'',true,true);

$snippets[5]= $modx->newObject('modSnippet');
$snippets[5]->fromArray(array(
    'id' => 5,
    'name' => 'FormItCountryOptions',
    'description' => 'A utility snippet for generating a dropdown list of countries.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.formitcountryoptions.php'),
),'',true,true);
$properties = include $sources['properties'].'properties.formitcountryoptions.php';
$snippets[5]->setProperties($properties);
unset($properties);

$snippets[6]= $modx->newObject('modSnippet');
$snippets[6]->fromArray(array(
    'id' => 6,
    'name' => 'FormItStateOptions',
    'description' => 'A utility snippet for generating a dropdown list of U.S. states.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.formitstateoptions.php'),
),'',true,true);
$properties = include $sources['properties'].'properties.formitstateoptions.php';
$snippets[6]->setProperties($properties);
unset($properties);

$snippets[7]= $modx->newObject('modSnippet');
$snippets[7]->fromArray(array(
    'id' => 7,
    'name' => 'FormItSaveForm',
    'description' => 'Save any form and export them to csv.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.formitsaveform.php'),
),'',true,true);

$snippets[8]= $modx->newObject('modSnippet');
$snippets[8]->fromArray(array(
    'id' => 8,
    'name' => 'FormItLoadSavedForm',
    'description' => 'Prehook to load previously saved form.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.formitloadsavedform.php'),
),'',true,true);
unset($properties);


/*
$snippets[0]= $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => '',
    'description' => '',
    'snippet' => getSnippetContent($sources['root'].'snippets/.snippet.php'),
),'',true,true);
$properties = include $sources['build'].'properties/properties..php';
$snippets[0]->setProperties($properties);
unset($properties);
*/

return $snippets;
