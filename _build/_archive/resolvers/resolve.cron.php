<?php
/**
 * Resolve cronjob information.
 *
 * @package formit
 * @subpackage build
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;

            $formitPath = $modx->getOption('formit.assets_path', null, MODX_ASSETS_PATH . 'components/formit/');
            $cronjobPath = $formitPath . 'cronjob/cron.php';

            $modx->log(modX::LOG_LEVEL_INFO,
                'You can setup a cronjob for automatically cleaning up of old forms, please use the following path: '
                . $cronjobPath
            );
            break;
    }
}
return true;