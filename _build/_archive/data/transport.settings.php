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
 * System Settings for FormIt
 *
 * @package formit
 * @subpackage build
 */
$settings = array();

$settings['formit.recaptcha_public_key']= $modx->newObject('modSystemSetting');
$settings['formit.recaptcha_public_key']->fromArray(array(
    'key' => 'formit.recaptcha_public_key',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'formit',
    'area' => 'recaptcha',
),'',true,true);

$settings['formit.recaptcha_private_key']= $modx->newObject('modSystemSetting');
$settings['formit.recaptcha_private_key']->fromArray(array(
    'key' => 'formit.recaptcha_private_key',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'formit',
    'area' => 'recaptcha',
),'',true,true);

$settings['formit.recaptcha_use_ssl']= $modx->newObject('modSystemSetting');
$settings['formit.recaptcha_use_ssl']->fromArray(array(
    'key' => 'formit.recaptcha_use_ssl',
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'formit',
    'area' => 'recaptcha',
),'',true,true);

$settings['formit.exclude_contexts']= $modx->newObject('modSystemSetting');
$settings['formit.exclude_contexts']->fromArray(array(
    'key' => 'formit.exclude_contexts',
    'value' => 'mgr',
    'xtype' => 'textfield',
    'namespace' => 'formit',
    'area' => '',
),'',true,true);

$settings['formit.attachment.mediasource']= $modx->newObject('modSystemSetting');
$settings['formit.attachment.mediasource']->fromArray(array(
    'key' => 'formit.attachment.mediasource',
    'value' => '1',
    'xtype' => 'modx-combo-source',
    'namespace' => 'formit',
    'area' => '',
),'',true,true);

$settings['formit.attachment.path']= $modx->newObject('modSystemSetting');
$settings['formit.attachment.path']->fromArray(array(
    'key' => 'formit.attachment.path',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'formit',
    'area' => '',
),'',true,true);

$settings['formit.cleanform.days']= $modx->newObject('modSystemSetting');
$settings['formit.cleanform.days']->fromArray(array(
    'key' => 'formit.cleanform.days',
    'value' => '90',
    'xtype' => 'numberfield',
    'namespace' => 'formit',
    'area' => '',
),'',true,true);

return $settings;