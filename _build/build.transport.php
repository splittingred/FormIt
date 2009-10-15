<?php
/**
 * Transport build script
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
define('PKG_VERSION','0.1');
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

/* add base chunks */
$modx->log(MODX_LOG_LEVEL_INFO,'Adding in chunks.'); flush();
$chunks = include_once $sources['data'].'transport.chunks.php';
if (is_array($chunks)) {
    $category->addMany($chunks);
} else { $modx->log(MODX_LOG_LEVEL_FATAL,'Adding chunks failed.'); }

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


/* load system settings */
$modx->log(MODX_LOG_LEVEL_INFO,'Adding in system settings.'); flush();
$settings = include_once $sources['data'].'transport.settings.php';

$attributes= array(
    XPDO_TRANSPORT_UNIQUE_KEY => 'key',
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => false,
);
foreach ($settings as $setting) {
    $vehicle = $builder->createVehicle($setting,$attributes);
    $builder->putVehicle($vehicle);
}
unset($settings,$setting,$attributes);

/* load action/menu */
$modx->log(MODX_LOG_LEVEL_INFO,'Adding in topmenu item.'); flush();
$menu = include_once $sources['data'].'transport.menu.php';
$vehicle= $builder->createVehicle($menu,array (
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
    XPDO_TRANSPORT_UNIQUE_KEY => 'text',
    XPDO_TRANSPORT_RELATED_OBJECTS => true,
    XPDO_TRANSPORT_RELATED_OBJECT_ATTRIBUTES => array (
        'Menus' => array (
            XPDO_TRANSPORT_PRESERVE_KEYS => false,
            XPDO_TRANSPORT_UPDATE_OBJECT => true,
            XPDO_TRANSPORT_UNIQUE_KEY => array ('namespace','controller'),
        ),
    ),
));
$builder->putVehicle($vehicle);
unset($vehicle,$menu);

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