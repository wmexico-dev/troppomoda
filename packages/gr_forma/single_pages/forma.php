<?php defined('C5_EXECUTE') or die(_("Access Denied."));
global $c;
if(empty($xcmd)) $xcmd = $this->controller->getTask();
Loader::model('grImagenes','gr_imagenes');
?>
 
<div class="row">
    <div class="small-12 column">
        <h2 class="tit-seccion">FORMA ENVIADA</h2>
        <p>Hemos recibido su infomación y nos comunicaremos a la brevedad.</p>
    </div>
</div>

<div class="contenido">
<?php $a= new Area('Contenido'); $a->display($c)?>
</div>
