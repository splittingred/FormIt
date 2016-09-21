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
            $modelPath = $modx->getOption('formit.core_path', null, $modx->getOption('core_path').'components/formit/').'model/';
            $modx->addPackage('formit', $modelPath);

            $manager = $modx->getManager();

            $manager->createObjectContainer('FormItForm');

            break;
        case xPDOTransport::ACTION_UPGRADE:
            /** @var modX $modx */
            $modx =& $object->xpdo;
            // http://forums.modx.com/thread/88734/package-version-check#dis-post-489104
            $c = $modx->newQuery('transport.modTransportPackage');
            $c->where(array(
                'workspace' => 1,
                "(SELECT
                    `signature`
                  FROM {$modx->getTableName('modTransportPackage')} AS `latestPackage`
                  WHERE `latestPackage`.`package_name` = `modTransportPackage`.`package_name`
                  ORDER BY
                     `latestPackage`.`version_major` DESC,
                     `latestPackage`.`version_minor` DESC,
                     `latestPackage`.`version_patch` DESC,
                     IF(`release` = '' OR `release` = 'ga' OR `release` = 'pl','z',`release`) DESC,
                     `latestPackage`.`release_index` DESC
                  LIMIT 1,1) = `modTransportPackage`.`signature`",
            ));
            $c->where(array(
                'modTransportPackage.package_name' => 'formit',
                'installed:IS NOT' => null
            ));
            /** @var modTransportPackage $oldPackage */
            $oldPackage = $modx->getObject('transport.modTransportPackage', $c);
            $modelPath = $modx->getOption('formit.core_path', null, $modx->getOption('core_path').'components/formit/').'model/';
            $modx->addPackage('formit', $modelPath);
            
            if ($oldPackage && $oldPackage->compareVersion('2.2.2-pl', '>')) {
                $manager = $modx->getManager();
                $manager->createObjectContainer('FormItForm');
            }

            if ($oldPackage && $oldPackage->compareVersion('2.2.9-pl', '>')) {
                $manager = $modx->getManager();
                $manager->addField('FormItForm', 'encrypted');
            }
            if ($oldPackage && $oldPackage->compareVersion('2.2.11-pl', '>')) {
                $manager = $modx->getManager();
                $manager->addField('FormItForm', 'hash');
            }
            break;
    }
}
return true;
