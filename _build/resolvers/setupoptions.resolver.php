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

                if ($settingObject) {
                    $settingObject->set('value', $options[$key]);
                    $settingObject->save();
                } else {
                    $error = '[' . $package . '] ' . strtolower($package) . '.' . $key . ' setting could not be found,';
                    $error .= ' so the setting could not be changed.';

                    $object->xpdo->log(
                        xPDO::LOG_LEVEL_ERROR,
                        $error
                    );
                }
            }
        }

        $success = true;
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        $success = true;
        break;
}

return $success;
