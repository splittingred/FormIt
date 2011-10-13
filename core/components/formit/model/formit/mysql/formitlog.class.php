<?php
/**
 * @package formit
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/formitlog.class.php');
class FormItLog_mysql extends FormItLog {}
?>