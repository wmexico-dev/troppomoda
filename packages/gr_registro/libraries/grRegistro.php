<?php  
Loader::model('grRegistro','gr_registro');
if(!is_array($_SESSION['grRegistro']) || empty($_SESSION['grRegistro']['t'])) grRegistro::inicio();
?>