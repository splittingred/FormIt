<?php
/**
 * Custom output filter that returns checked="checked" if the value is set
 *
 * @package formit
 */
$output = ' ';
if ($input == $options) {
    $output = ' selected="selected"';
}
if (strpos($input,',') !== false) {
    $input = explode(',',$input);
    if (in_array($options,$input)) {
        $output = ' selected="selected"';
    }
}
return $output;
