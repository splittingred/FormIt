<?php

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;

            $path = $modx->getOption('formit.assets_path', null, MODX_ASSETS_PATH . 'components/formit/') . 'cronjob/cron.php';

            $modx->log(modX::LOG_LEVEL_INFO, 'You can setup a cronjob for automatically cleaning up of old forms, please use the following path: ' . $path);

            break;
    }
}

return true;
