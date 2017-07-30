<?php defined('C5_EXECUTE') or die(_("Access Denied."));

$this->inc('inc/inicio.php')?> 

<div id="contenido especial">
<?php $a= new Area('Contenido'); $a->display($c)?>
</div>

<?php $this->inc('inc/fin.php')?>