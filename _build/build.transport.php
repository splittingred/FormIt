<?php
/**
 * FormIt
 *
 * Copyright 2009 by Shaun McCormick <shaun@collabpad.com>
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
 * FormIt transport package build script
 *
 * @package formit
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* set package defines */
define('PKG_ABBR','formit');
define('PKG_NAME','FormIt');
define('PKG_VERSION','1.0');
define('PKG_RELEASE','alpha1');

/* override with your own defines here (see build.config.sample.php) */
require_once dirname(__FILE__) . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
require_once dirname(__FILE__). '/includes/functions.php';

$modx= new modX();
$root = dirname(dirname(__FILE__)).'/';
$assets = MODX_ASSETS_PATH.'components/'.PKG_ABBR.'/';
$sources= array (
    'root' => $root,
    'build' => $root .'_build/',
    'resolvers' => $root . '_build/resolvers/',
    'data' => $root . '_build/data/',
    'source_core' => $root,
    'source_assets' => $assets,
    'lexicon' => $root.'lexicon/',
    'docs' => $root.'docs/',
);
unset($root);

$modx->initialize('mgr');
echo '<pre>';
$modx->setLogLevel(MODX_LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_ABBR,PKG_VERSION,PKG_RELEASE);
$builder->registerNamespace(PKG_ABBR,false,true,'{core_path}components/'.PKG_ABBR.'/');

/* create category */
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category',PKG_NAME);

/* add base snippets */
$modx->log(MODX_LOG_LEVEL_INFO,'Adding in snippets.'); flush();
$snippets = include_once $sources['data'].'transport.snippets.php';
if (is_array($snippets)) {
    $category->addMany($snippets);
} else { $modx->log(MODX_LOG_LEVEL_FATAL,'Adding snippets failed.'); }

/* create base category vehicle */
$attr = array(
    XPDO_TRANSPORT_UNIQUE_KEY => 'category',
    XPDO_TRANSPORT_PRESERVE_KEYS => false,
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
    XPDO_TRANSPORT_RELATED_OBJECTS => true,
    XPDO_TRANSPORT_RELATED_OBJECT_ATTRIBUTES => array (
        'Children' => array(
            XPDO_TRANSPORT_PRESERVE_KEYS => false,
            XPDO_TRANSPORT_UPDATE_OBJECT => true,
            XPDO_TRANSPORT_UNIQUE_KEY => 'category',
            XPDO_TRANSPORT_RELATED_OBJECTS => true,
            XPDO_TRANSPORT_RELATED_OBJECT_ATTRIBUTES => array (
                'Snippets' => array(
                    XPDO_TRANSPORT_PRESERVE_KEYS => false,
                    XPDO_TRANSPORT_UPDATE_OBJECT => true,
                    XPDO_TRANSPORT_UNIQUE_KEY => 'name',
                ),
                'Chunks' => array(
                    XPDO_TRANSPORT_PRESERVE_KEYS => false,
                    XPDO_TRANSPORT_UPDATE_OBJECT => true,
                    XPDO_TRANSPORT_UNIQUE_KEY => 'name',
                ),
            ),
        ),
        'Snippets' => array(
            XPDO_TRANSPORT_PRESERVE_KEYS => false,
            XPDO_TRANSPORT_UPDATE_OBJECT => true,
            XPDO_TRANSPORT_UNIQUE_KEY => 'name',
        ),
        'Chunks' => array(
            XPDO_TRANSPORT_PRESERVE_KEYS => false,
            XPDO_TRANSPORT_UPDATE_OBJECT => true,
            XPDO_TRANSPORT_UNIQUE_KEY => 'name',
        ),
    )
);
$vehicle = $builder->createVehicle($category,$attr);
$vehicle->resolve('file',array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));
$vehicle->resolve('file',array(
    'source' => $sources['source_assets'],
    'target' => "return MODX_ASSETS_PATH . 'components/';",
));
$builder->putVehicle($vehicle);

/* load lexicon strings */
$builder->buildLexicon($sources['lexicon']);

/* now pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
));

$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(MODX_LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

exit ();