<?php

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modx->addPackage('formit', $modx->getOption('formit.core_path', null, $modx->getOption('core_path') . 'components/formit/') . 'model/');

            $manager = $modx->getManager();

            $manager->createObjectContainer('FormItForm');

            break;
    }
}

return true;
