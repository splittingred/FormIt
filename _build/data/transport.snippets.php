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