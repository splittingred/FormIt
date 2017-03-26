<?php
/**
 * FormIt setup options resolver
 *
 * @package FormIt
 * @subpackage build
 */
$package = 'FormIt';

$success = false;
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $settings = array(
            'user_name',
            'user_email',
        );
        foreach ($settings as $key) {
            if (isset($options[$key])) {
                $settingObject = $object->xpdo->getObject(
                    'modSystemSetting',
                    array('key' => strtolower($package) . '.' . $key)
                );
                if (!$settingObject) {
                    $settingObject = $object->xpdo->newObject('modSystemSetting');
                    $settingObject->set('key', strtolower($package) . '.' . $key);
                }
                $settingObject->set('value', $options[$key]);
                $settingObject->save();
            }
        }

        $success = true;
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        $success = true;
        break;
}

return $success;
