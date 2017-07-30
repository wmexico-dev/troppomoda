<?php  
Loader::model('grCompras','gr_tienda');
if(!is_array($_SESSION['grCompras']) || empty($_SESSION['grCompras']['t'])) grCompras::nuevo();
?>