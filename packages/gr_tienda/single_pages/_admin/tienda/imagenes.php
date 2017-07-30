<?php defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd = $this->controller->getTask();
?>

<div id="admin">
<div class="row">

<?php if($xcmd=='agregar' || $xcmd=='editar') {?>

    <h2 class="titulo">Admin Imágenes : <?php echo ucfirst($xcmd)?> Información<br /><a class="link" href="/_admin">Administración</a><a class="link" href="/_admin/tienda/">Admin Tienda</a><?php if($xcmd=='editar') {?><a class="link" href="#" onClick="eliminar();return false;">Eliminar Imagen</a><?php }?></h2>
    
    <div class="small-8 small-offset-1 column end">
    <?php if(is_array($xerr)) { echo '<div class="error"><ul>'; foreach ($xerr as $e) echo'<li>'.$e.'</li>'; echo "</ul></div>\n"; }?>

    <form role="form" class="forma" name="forma" method="post" action="<?php echo $this->action('actualizar')?>" enctype="multipart/form-data">
    <input name="xcmd" type="hidden" value="<?php echo $xcmd?>" /><input name="iID" type="hidden" value="<?php echo $xdat['iID']?>" /><input name="tID" type="hidden" value="<?php echo $xdat['tID']?>" /><input name="iTipo" type="hidden" value="<?php echo $xdat['iTipo']?>" /><input name="iImagen" type="hidden" value="<?php echo $xdat['iImagen']?>" /><input name="iIndice" type="hidden" value="<?php echo $xdat['iIndice']?>" />
    <div class="row">
        <div class="small-12 columns">
        <label>Imagen:
            <input name="archivoImagen" type="file">
<?php if(!empty($xdat['iImagen'])) echo '<div class="row"><div class="small-6 column end"><a class="th" href="#"><img src="/files/tienda/a'.$xdat['iImagen'].'.jpg?t='.time().'" alt="" /></a></div></div>'?>
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>
            <input type="checkbox" name="imagenPrincipal" value="1" /> Hacerla la imagen principal del producto
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Nombre de la Imagen:  &nbsp;<span class="gris">(opcional)</span>
            <input class="formaInput form-control" name="iNombre" type="text" value="<?php echo $xdat['iNombre']?>" />
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Link:  &nbsp;<span class="gris">(opcional)</span>
            <input class="formaInput form-control" name="iLink" type="text" value="<?php echo $xdat['iLink']?>" />
        </label>
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

    <h2 class="titulo">Admin Imágenes<br /><a class="link" href="/_admin">Administración</a><a class="link" href="/_admin/tienda/">Admin Tienda</a><a class="link" href="<?php echo $this->action('agregar')?>">Agregar Imagen</a></h2>
    
    <div class="small-8 small-offset-1 column end">
    <div class="lista">
    <?php
        echo "<ul class=\"small-block-grid-2 medium-block-grid-3 large-block-grid-4\">\n";
        if(is_array($xdat)) foreach($xdat as $L){
            echo '<li><a class="th" href="'.$this->action('editar',$L['iID']).'"><img src="/files/tienda/a'.$L['iImagen'].'.jpg" alt="" /></a><br />';
            if(empty($L['iNombre'])) echo $L['iID']; else echo $L['iNombre'];
            echo "</li>\n";
        }
        echo "</ul>\n";
    ?>
    </div>
    </div>

<?php }?>

</div>
</div>
