<?php defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd = $this->controller->getTask();
$db= Loader::db();
Loader::model('grImagenes','gr_imagenes');
$u= new User();
?>

<div class="no-imagenes" style="height:100px"></div>  

<div class="espacio doble">
<div class="row rel">
    <div class="col-i" style="width:190px">
<?php grImagenes::columna()?>

<?php $a= new Area('Izquierda'); $a->display($c)?>
    </div>
    <div class="small-12 column" style="padding-left:230px">
        <h2 class="tit-seccion">Textos</h2>


    </div>
</div>    
</div>

