<?php
// exit(MODX_CORE_PATH.'export/FormItForm/'.$_GET['file']);
header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="'.$_GET['file'].'"');
readfile(MODX_CORE_PATH.'export/FormItForm/'.$_GET['file']);

?>