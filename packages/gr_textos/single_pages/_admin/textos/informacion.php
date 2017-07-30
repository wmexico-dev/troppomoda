<?php defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd = $this->controller->getTask();
?>

<div id="admin">
<div class="row">
    
<?php if($xcmd=='agregar' || $xcmd=='editar') {?>

    <h2 class="titulo">Admin Textos : <?php echo ucfirst($xcmd)?> Información<br /><?php echo $xdat['dTitulo']?><br /><a class="link" href="/_admin/">Administración</a><a class="link" href="/_admin/textos/">Administración Textos</a><a class="link" href="/_admin/textos/informacion/<?php echo $xdat['dID']?>/">Admin Información Texto</a><?php if($xcmd=='editar' && !empty($xdat['iTipo'])) {?><a class="link" href="#" onClick="eliminar();return false;">Eliminar Información</a><?php  }?></h2>
    
    <div class="small-8 small-offset-3 column end">
    <?php if(is_array($xerr)) { echo '<div class="error"><ul>'; foreach ($xerr as $e) echo'<li>'.$e.'</li>'; echo "</ul></div>\n"; }?>

    <form role="form" class="forma" name="forma" method="post" action="<?php echo $this->action('actualizar')?>">
    <input type="hidden" name="xcmd" value="<?php echo $xcmd?>" /><input type="hidden" name="iID" value="<?php echo $xdat['iID']?>" /><input type="hidden" name="dID" value="<?php echo $xdat['dID']?>" />
    <div class="row">
        <div class="small-12 columns">
        <label>Título:
            <input class="formaInput form-control" name="iTitulo" type="text" value="<?php echo $xdat['iTitulo']?>" />
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Información:
            <textarea class="formaArea form-control ccm-advanced-editor" name="iInfo" rows="6"><?php echo $xdat['iInfo']?></textarea>
        </label>
        </div>
    </div>
    <div class="row mt10">
        <div class="small-12 columns">
            <input class="formaSubmit button radius" type="submit" value="Actualizar" />
        </div>
    </div>
    </form>
    
    </div>

<?php } else {?>
    
    <h2 class="titulo">Admin Textos : Información<br /><?php echo $xdat['dTitulo']?><br /><a class="link" href="#reordenar" id="guardarorden" style="visibility:hidden;color:#AF7D00" onclick="return reordenar();">Guardar Nuevo Orden</a><a class="link" href="/_admin/">Administración</a><a class="link" href="/_admin/textos/">Admin Textos</a><?php if($xcmd!='ordenable'){?><a class="link" href="<?php echo $this->action('ordenable',$xdat['dID'])?>">Reordenar Lista Info</a><?php }?><a class="link" href="<?php echo $this->action('agregar',$xdat['dID'])?>">Nueva Información</a></h2>
    
    <div class="small-8 small-offset-3 column end">
    <div class="lista">
        <ul class="ordenable l-mb">
<?php   
$db = Loader::db();
$rq = $db->query("SELECT * FROM grTextosInformacion WHERE iTipo=10 AND dID=".$xdat['dID'].' ORDER BY iIndice, iTitulo');
while($I=$rq->fetchrow()) {
    echo '<li id="n_'.$I['iID'].'">'.$I['iTitulo'];
    if($xcmd!='ordenable') echo' &nbsp;<a class="link" href="'.$this->action('editar',$I['iID']).'">Editar&nbsp;Información</a>';
    echo"</li>\n";
}?>
        </ul>
    </div>
    </div>

<?php }?>

</div>
</div>
