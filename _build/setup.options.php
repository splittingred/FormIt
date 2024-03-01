<?php

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

$package = 'FormIt';

$settings = [[
    'key'   => 'user_name',
    'value' => '',
    'name'  => 'Name'
], [
    'key'   => 'user_email',
    'value' => '',
    'name'  => 'Email address'
]];

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        foreach ($settings as $key => $value) {
            $setting = $modx->getObject('modSystemSetting', [
                'key' => strtolower($package) . '.' . $value['key']
            ]);

            if ($setting) {
                $settings[$key]['value'] = $setting->get('value');
            }
        }
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

/* Hide default setuptoptions text */
$output[] = '<style type="text/css">
    #modx-setupoptions-panel { display: none; }
</style>

<script>
    var setupTitle = "FormIt installation - a MODX Extra by Sterc";
    document.getElementsByClassName("x-window-header-text")[0].innerHTML = setupTitle;
</script>

<h2>Get free priority updates</h2>
<p>Enter your name and email address below to receive priority updates about our extras.
Be the first to know about updates and new features.
<i><b>It is NOT required to enter your name and email to use this extra.</b></i></p>';

foreach ($settings as $setting) {
    $str = '<label for="'. $setting['key'] .'">'. $setting['name'] .' (optional)</label>';
    $str .= '<input type="text" name="'. $setting['key'] .'"';
    $str .= ' id="'. $setting['key'] .'" width="300" value="'. $setting['value'] .'" />';

    $output[] = $str;
}

return implode('<br /><br />', $output);
