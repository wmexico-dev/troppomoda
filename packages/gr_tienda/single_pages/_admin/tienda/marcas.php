<?php defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd = $this->controller->getTask();
?>

<div id="admin">
<div class="row">

<?php if($xcmd=='agregar' || $xcmd=='editar') {?>

    <h2 class="titulo">Admin Marcas : <?php echo ucfirst($xcmd)?> Información<br /><a class="link" href="/_admin">Administración</a><a class="link" href="/_admin/tienda/">Admin Tienda</a><a class="link" href="/_admin/tienda/marcas/">Admin Marcas</a><?php if($xcmd=='editar') {?><a class="link" href="#" onClick="eliminar();return false;">Eliminar Marca</a><?php }?></h2>
    
    <div class="small-8 small-offset-1 column end">
    <?php if(is_array($xerr)) { echo '<div class="error"><ul>'; foreach ($xerr as $e) echo'<li>'.$e.'</li>'; echo "</ul></div>\n"; }?>

    <form role="form" class="forma" name="forma" method="post" action="<?php echo $this->action('actualizar')?>" enctype="multipart/form-data">
    <input name="xcmd" type="hidden" value="<?php echo $xcmd?>" /><input name="mID" type="hidden" value="<?php echo $xdat['mID']?>" /><input name="mTipo" type="hidden" value="<?php echo $xdat['mTipo']?>" /><input name="mImagen" type="hidden" value="<?php echo $xdat['mImagen']?>" /><input name="mIndice" type="hidden" value="<?php echo $xdat['mIndice']?>" />
    <div class="row">
        <div class="small-12 columns">
        <label>Nombre de la Marca:
            <input class="formaInput form-control" name="mNombre" type="text" value="<?php echo $xdat['mNombre']?>" />
        </label>
        </div>
    </div>
    <div class="row" style="margin-top:1rem">
        <div class="small-12 columns">
        <div class="panel">
        <h6>Descuento VIP en Marca</h6>
        <div class="row">
            <div class="small-12 columns">
            <input id="mVipStatus" name="mVipStatus" type="checkbox" value="1"<?php if(!empty($xdat['mVipStatus'])) echo' checked'?>><label for="mVipStatus">Activar descuento VIP</label>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
            <label>Porcentaje de Descuento:
                <input class="formaInput" name="mVipDescuento" type="text" value="<?php echo $xdat['mVipDescuento']?>" />
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

    <h2 class="titulo">Admin Marcas<br /><a class="link" href="/_admin">Administración</a><a class="link" href="/_admin/tienda/">Admin Tienda</a><a class="link" href="<?php echo $this->action('agregar')?>">Agregar Marca</a></h2>
    
    <div class="small-8 small-offset-2 column end">
    <div class="lista">
        <ul>
<?php   
$db = Loader::db();
$rq = $db->query("SELECT *, (SELECT count(t.tMarca) FROM grTienda as t WHERE t.tMarca=m.mID) as numTienda FROM grTiendaMarcas as m WHERE mID>0 ORDER by mIndice, mNombre");
while($C=$rq->fetchrow()) if($C['mID']>100) {
    echo '<li>';
    echo $C['mNombre'];
    if(!empty($C['numTienda'])) echo ' &nbsp;<span style="font-size:12px;color:#CCCCCC">('.$C['numTienda'].')</span>';
    if(!empty($C['mVipStatus'])) echo' &nbsp; <span style="font-size:11px;color:#993333">-<i class="fa fa-percent"></i>VIP</span>';
    echo ' &nbsp;<a class="link" href="'.$this->action('editar',$C['mID']).'">Editar&nbsp;Marca</a>';
    echo "</li>\n";
}
?>
        </ul>
    </div>
    </div>

<?php }?>

</div>
</div>
