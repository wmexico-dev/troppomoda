<?php defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd = $this->controller->getTask();
?>

<div id="admin" class="row">

<?php if($xcmd=='agregar' || $xcmd=='editar') {?>

    <div class="small-12 columns">
    <h2 class="titulo">Admin Imágenes : <?php echo ucfirst($xcmd)?> Información<br /><a class="link" href="/_admin">Administración</a><a class="link" href="/_admin/imagenes/">Admin Imágenes</a><?php if($xcmd=='editar') {?><a class="link" href="#" onClick="eliminar();return false;">Eliminar Imagen</a><?php }?></h2>
    </div>
    
    <div class="small-6 small-offset-3 column end">
    <?php if(is_array($xerr)) { echo '<div class="error"><ul>'; foreach ($xerr as $e) echo'<li>'.$e.'</li>'; echo "</ul></div>\n"; }?>

    <form role="form" class="forma" name="forma" method="post" action="<?php echo $this->action('actualizar')?>" enctype="multipart/form-data">
    <input name="xcmd" type="hidden" value="<?php echo $xcmd?>" /><input name="iID" type="hidden" value="<?php echo $xdat['iID']?>" /><input name="iImagen" type="hidden" value="<?php echo $xdat['iImagen']?>" />
<?php
    $Cat = null;
    $db = Loader::db();
    $rq = $db->query("SELECT * FROM grImagenesCategorias WHERE cID>0 ORDER BY cIndice, cNombre");
    while($rx=$rq->fetchrow()) $Cat[]=$rx;
    if(!empty($Cat)) {
?>
   <div class="row">
        <div class="small-12 columns">
        <label>Categoría:
            <select name="cID" class="formaSelect">
                <option value="0">Sin Categoría</option>
<?php foreach($Cat as $cx) { echo '<option value="'.$cx['cID'].'"'; if($cx['cID']==$xdat['cID']) echo ' selected'; echo '>'.$cx['cNombre']."</option>\n"; } ?>
            </select>
        </label>
        </div>
    </div>
<?php  }?>
    <div class="row">
        <div class="small-12 columns">
        <label>Imagen:
            <input name="archivoImagen" type="file">
<?php if(!empty($xdat['iImagen'])) echo '<div class="row"><div class="small-6 column end"><a class="th" href="#"><img src="/files/imagenes/i'.$xdat['iImagen'].'.jpg?t='.time().'" alt="" /></a></div></div>';?>
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Nombre de la Imagen:  &nbsp;(opcional)
            <input class="formaInput form-control" name="iNombre" type="text" value="<?php echo $xdat['iNombre']?>" />
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Link:  &nbsp;(opcional)
            <input class="formaInput form-control" name="iLink" type="text" value="<?php echo $xdat['iLink']?>" />
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Nota:  &nbsp;(opcional)
            <textarea class="formaArea form-control" name="iNota" rows="3"><?php echo $xdat['iNota']?></textarea>
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

    <h2 class="titulo">Admin Imágenes<br /><a class="link" href="/_admin">Administración</a><a class="link" href="<?php echo $this->action('agregar')?>">Agregar Imagen</a></h2>
    
    <div class="small-8 small-offset-3 column end">
    <div class="lista">
    <?php  
        $db = Loader::db();
        $rq = $db->query('SELECT * FROM grImagenes as i, grImagenesCategorias as c WHERE i.cID=c.cID ORDER BY c.cIndice, c.cNombre, i.iNombre, i.iID'); 
        $cc = -1;
        while($L=$rq->fetchrow()) {
            if($L['cID']!=$cc) {
                if($cc>-1) echo "</ul>\n";
                echo '<h4>'.$L['cNombre']."</h4>\n<ul class=\"small-block-grid-2 medium-block-grid-3 large-block-grid-4\">\n";
                $cc = $L['cID'];
            }
            echo '<li>';
            echo '<a class="th" href="'.$this->action('editar',$L['iID']).'"><img src="/files/imagenes/i';
            if(!empty($L['iImagen'])) echo $L['iImagen'];
            echo '.jpg?v='.time().'" alt="" /></a><br />';
            if(empty($L['iNombre'])) echo $L['iID']; else echo $L['iNombre'];
            echo "</li>\n";
        }
        echo "</ul>\n";
    ?>
    </div>
    </div>

<?php }?>

</div>
