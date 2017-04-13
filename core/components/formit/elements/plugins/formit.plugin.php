<?php
/**
 * FormIt
 *
 * Copyright 2009-2017 by Sterc <modx@sterc.nl>
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
 * FormIt plugin
 *
 * @package formit
 */

$formit = $modx->getService(
    'formit',
    'FormIt',
    $modx->getOption('formit.core_path', null, $modx->getOption('core_path').'components/formit/') .'model/formit/',
    array()
);

if (!($formit instanceof FormIt)) {
    return;
}

switch ($modx->event->name) {
    case 'OnManagerPageBeforeRender':
        // If migration status is false, show migrate alert message bar in manager
        if (method_exists('FormIt','encryptionMigrationStatus')) {
            if (!$formit->encryptionMigrationStatus()) {
                $modx->lexicon->load('formit:mgr');
                $properties = array('message' => $modx->lexicon('formit.migrate_alert'));
                $chunk = $formit->_getTplChunk('migrate/alert');
                if ($chunk) {
                    $modx->regClientStartupHTMLBlock($chunk->process($properties));
                    $modx->regClientCSS($formit->config['cssUrl'] . 'migrate.css');
                }
            }
        }
}