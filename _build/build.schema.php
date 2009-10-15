<?php
/**
 * Build Schema script
 *
 * @package formit
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

include_once dirname(__FILE__).'/build.config.php';
include_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
$modx->loadClass('transport.modPackageBuilder','',false, true);
echo '<pre>'; /* used for nice formatting of log messages */
$modx->setLogLevel(MODX_LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$root = MODX_BASE_PATH;
$sources = array(
    'root' => $root,
    'core' => $root.'core/components/formit/',
    'model' => $root.'core/components/formit/model/',
    'assets' => $root.'assets/components/formit/',
);
$manager= $modx->getManager();
$generator= $manager->getGenerator();

$generator->classTemplate= <<<EOD
<?php
/**
 * [+phpdoc-package+]
 */
class [+class+] extends [+extends+] {
    function [+class+](& \$xpdo) {
        \$this->__construct(\$xpdo);
    }
    function __construct(& \$xpdo) {
        parent :: __construct(\$xpdo);
    }
}
?>
EOD;
$generator->platformTemplate= <<<EOD
<?php
/**
 * [+phpdoc-package+]
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\\\', '/') . '/[+class-lowercase+].class.php');
class [+class+]_[+platform+] extends [+class+] {
    function [+class+]_[+platform+](& \$xpdo) {
        \$this->__construct(\$xpdo);
    }
    function __construct(& \$xpdo) {
        parent :: __construct(\$xpdo);
    }
}
?>
EOD;
$generator->mapHeader= <<<EOD
<?php
/**
 * [+phpdoc-package+]
 */
EOD;
$generator->parseSchema(dirname(__FILE__) . '/formit.mysql.schema.xml', $sources['model']);


$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n";

exit ();