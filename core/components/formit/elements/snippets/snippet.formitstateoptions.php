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
 * FormItStateOptions
 *
 * Automatically generates and outputs a U.S. state list for usage in forms
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package formit
 */

$modelPath = $modx->getOption(
    'formit.core_path',
    null,
    $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/formit/'
) . 'model/formit/';
$fi = $modx->getService('formit', 'FormIt', $modelPath, $scriptProperties);

/** @var fiStateOptions $so */
$so = $fi->loadModule('fiStateOptions', 'stateOptions', $scriptProperties);
$so->initialize();
$so->getData();
$so->iterate();
return $so->output();
