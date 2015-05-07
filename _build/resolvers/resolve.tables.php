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
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('formit.core_path',null,$modx->getOption('core_path').'components/formit/').'model/';
            $modx->addPackage('formit',$modelPath);

            $manager = $modx->getManager();

            $manager->createObjectContainer('FormItForm');

            break;
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('formit.core_path',null,$modx->getOption('core_path').'components/formit/').'model/';
            $modx->addPackage('formit',$modelPath);

            $manager = $modx->getManager();
            $manager->createObjectContainer('FormItForm'); // Dirty fix for upgrading from an older FormIt 
            $manager->addField('FormItForm', 'encrypted');


            break;
    }
}
return true;