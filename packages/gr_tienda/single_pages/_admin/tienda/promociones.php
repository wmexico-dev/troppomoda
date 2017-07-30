<?php defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd = $this->controller->getTask();
if(empty($xdat['pColor'])) $xdat['pColor']='#444444';
?>

<div id="admin">
<div class="row">

<?php if($xcmd=='agregar' || $xcmd=='editar') {?>

    <h2 class="titulo">Admin Promociones : <?php echo ucfirst($xcmd)?> Información<br /><a class="link" href="/_admin">Administración</a><a class="link" href="/_admin/tienda/">Admin Tienda</a><a class="link" href="/_admin/tienda/promociones/">Admin Promociones</a><?php if($xcmd=='editar') {?><a class="link" href="#" onClick="eliminar();return false;">Eliminar Promoción</a><?php }?></h2>
    
    <div class="small-8 small-offset-1 column end">
    <?php if(is_array($xerr)) { echo '<div class="error"><ul>'; foreach ($xerr as $e) echo'<li>'.$e.'</li>'; echo "</ul></div>\n"; }?>

    <form role="form" class="forma" name="forma" method="post" action="<?php echo $this->action('actualizar')?>" enctype="multipart/form-data">
    <input name="xcmd" type="hidden" value="<?php echo $xcmd?>" /><input name="pID" type="hidden" value="<?php echo $xdat['pID']?>" /><input name="pTipo" type="hidden" value="<?php echo $xdat['pTipo']?>" /><input name="pImagen" type="hidden" value="<?php echo $xdat['pImagen']?>" /><input name="pIndice" type="hidden" value="<?php echo $xdat['pIndice']?>" />
    <div class="row">
        <div class="small-12 columns">
        <label>Nombre de la Promoción:
            <input class="formaInput form-control" name="pNombre" type="text" value="<?php echo $xdat['pNombre']?>" />
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Color:<br/>
            <input class="formaInput form-control" id="pColor" name="pColor" type="text" style="display:inline-block;width:100px" value="<?php echo $xdat['pColor']?>" /></br>
            <i id="color" class="fa fa-th-large" style="font-size:40px;color:<?php echo $xdat['pColor']?>"></i>
        </label>
        </div>
    </div>
    <div class="row" style="margin-top:1rem">
        <div class="small-12 columns">
        <div class="panel">
        <h6>Descuento VIP en Promoción</h6>
        <div class="row">
            <div class="small-12 columns">
            <input id="pVipStatus" name="pVipStatus" type="checkbox" value="1"<?php if(!empty($xdat['pVipStatus'])) echo' checked'?>><label for="pVipStatus">Activar descuento VIP</label>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
            <label>Porcentaje de Descuento:
                <input class="formaInput" name="pVipDescuento" type="text" value="<?php echo $xdat['pVipDescuento']?>" />
            </label>
            </div>
        </div>
        </div>
        </div>
    </div>
    <div class="row mt10">
        <div class="small-12 columns">
        <label>
            <input class="formaSubmit button radius" type="submit" value="Actualizar" />
        </label>
        </div>
    </div>
    </form>
    
    </div>

<?php } else {?>

    <h2 class="titulo">Admin Promociones<br /><a class="link" href="/_admin">Administración</a><a class="link" href="/_admin/tienda/">Admin Tienda</a><a class="link" href="<?php echo $this->action('agregar')?>">Agregar Promoción</a></h2>
    
    <div class="small-10 small-offset-1 column end">
    <div class="lista">
        <ul style="list-style:none">
<?php
$db = Loader::db();
$rq = $db->query("SELECT *, (SELECT count(t.tPromocion) FROM grTienda as t WHERE t.tPromocion=p.pID) as numTienda FROM grTiendaPromociones as p WHERE pID>100 ORDER by pIndice, pNombre");
while($C=$rq->fetchrow()){
    echo '<li><i class="fa fa-certificate" style="padding-right:10px;color:'.$C['pColor'].'"></i>';
    echo $C['pNombre'];
    if(!empty($C['numTienda'])) echo ' &nbsp;<span style="font-size:12px;color:#CCCCCC">('.$C['numTienda'].')</span>';
    if(!empty($C['pVipStatus'])) echo' &nbsp; <span style="font-size:11px;color:#993333">-<i class="fa fa-percent"></i>VIP</span>';
    echo ' &nbsp;<a class="link" href="'.$this->action('editar',$C['pID']).'">Editar&nbsp;Promoción</a>';
    echo "</li>\n";
}
?>
        </ul>
    </div>
    </div>

<?php }?>

</div>
</div>
