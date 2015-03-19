<?php
/**
 * Resolve creating db tables
 *
 * @package formit
 * @subpackage build
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('formit.core_path',null,$modx->getOption('core_path').'components/formit/').'model/';
            $modx->addPackage('formit',$modelPath);

            $manager = $modx->getManager();

            $manager->createObjectContainer('FormItForm');

            break;
    }
}
return true;