<?php

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

$package = 'FormIt';

$settings = ['user_name', 'user_email'];

$success = false;

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        foreach ($settings as $key) {
            if (isset($options[$key])) {
                $setting = $modx->getObject('modSystemSetting', [
                    'key' => strtolower($package) . '.' . $key
                ]);

                if ($setting) {
                    $setting->set('value', $options[$key]);
                    $setting->save();
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
