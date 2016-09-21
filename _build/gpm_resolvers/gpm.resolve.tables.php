<?php
/**
 * Resolve creating db tables
 *
 * THIS RESOLVER IS AUTOMATICALLY GENERATED, NO CHANGES WILL APPLY
 *
 * @package formit
 * @subpackage build
 */

if ($object->xpdo) {
    $modx =& $object->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modelPath = $modx->getOption('formit.core_path', null, $modx->getOption('core_path') . 'components/formit/') . 'model/';
            
            $modx->addPackage('formit', $modelPath, null);


            $manager = $modx->getManager();

            $manager->createObjectContainer('FormItForm');

            break;
    }
}

return true;