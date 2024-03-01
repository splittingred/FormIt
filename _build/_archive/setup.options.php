<?php
/**
 * FormIt setup options
 *
 * @package FormIt
 * @subpackage build
 */
$package = 'FormIt';

$settings = array(
    array(
        'key' => 'user_name',
        'value' => '',
        'name' => 'Name'
    ),
    array(
        'key' => 'user_email',
        'value' => '',
        'name' => 'Email'
    ),
);
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        foreach ($settings as $key => $setting) {
            $settingObject = $modx->getObject(
                'modSystemSetting',
                array('key' => strtolower($package) . '.' . $setting['key'])
            );
            if ($settingObject) {
                $settings[$key]['value'] = $settingObject->get('value');
            }
        }
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

$output = array();

/* Hide default setuptoptions text */
$output[] = '
<style type="text/css">
    #modx-setupoptions-panel { display: none; }
</style>

<script>
    var setupTitle = "' . $package . ' installation - a MODX Extra by Sterc";
    document.getElementsByClassName("x-window-header-text")[0].innerHTML = setupTitle;
</script>

<h2>Get free priority updates</h2>
<p>Enter your name and email address below to receive priority updates about our extras.
Be the first to know about Extra updates and new features.
<i><b>It is not required to enter your name and email to use this extra.</b></i></p>';

foreach ($settings as $setting) {
    $str = '<label for="'. $setting['key'] .'">'. $setting['name'] .' (optional)</label>';
    $str .= '<input type="text" name="'. $setting['key'] .'"';
    $str .= ' id="'. $setting['key'] .'" width="300" value="'. $setting['value'] .'" />';

    $output[] = $str;
}

return implode('<br /><br />', $output);
