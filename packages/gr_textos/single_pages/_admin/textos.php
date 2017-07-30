<?php defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd = $this->controller->getTask();
$u= new User();
?>

<div id="admin">
<div class="row">

<?php if($xcmd=='agregar' || $xcmd=='editar') {?>

    <h2 class="titulo">Admin Textos : Información <br /><a class="link" href="/_admin">Administración</a><a class="link" href="/_admin/textos/">Admin Textos</a><?php if($xcmd=='editar' || $u->isSuperUser()) {?><a class="link" href="<?php echo $this->action('informacion/agregar',$xdat['dID'])?>">Agregar Más Información</a><a class="link" href="#" onClick="eliminar();return false;">Eliminar Texto</a><?php  }?></h2>
    
    <div class="small-8 small-offset-3 column end">
    <?php if(is_array($xerr)) { echo '<div class="error"><ul>'; foreach ($xerr as $e) echo'<li>'.$e.'</li>'; echo "</ul></div>\n"; }?>

    <form role="form" id="forma" class="forma" name="forma" method="post" action="<?php echo $this->action('actualizar')?>" enctype="multipart/form-data">
    <input name="xcmd" type="hidden" value="<?php echo $xcmd?>" /><input name="dID" type="hidden" value="<?php echo $xdat['dID']?>" /><input name="dTipo" type="hidden" value="<?php echo $xdat['dTipo']?>" /><input name="dImagen" type="hidden" value="<?php echo $xdat['dImagen']?>" />
<?php if($u->isSuperUser()) {?>
    <div class="row">
        <div class="small-12 columns">
        <label>Título del Texto:
            <input class="formaInput form-control" name="dTitulo" type="text" value="<?php echo $xdat['dTitulo']?>" required />
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Identificación del Texto:
            <input class="formaInput form-control" name="dIdent" type="text" value="<?php echo $xdat['dIdent']?>" required />
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Imagen:
            <input name="archivoImagen" type="file">
<?php if(!empty($xdat['dImagen'])) echo '<div class="row"><div class="small-6 column"><a class="th" href="#"><img src="/files/textos/i'.$xdat['dImagen'].'.jpg?t='.time().'" alt="" /></a></div><div class="small-6 column"><input type="checkbox" name="eliminarImagen" value="1" /> Eliminar Imagen</div></div>'?>
        </label>
        </div>
    </div>
<?php } else {?>
    <input name="dTitulo" type="hidden" value="<?php echo $xdat['dTitulo']?>" /><input name="dIdent" type="hidden" value="<?php echo $xdat['dIdent']?>" required />
    <div class="row">
        <h3><?php echo $xdat['dTitulo']?></h3>
    </div>
<?php }?>
    <div class="row">
        <div class="small-12 columns">
        <label>Texto:
            <textarea class="formaArea form-control ccm-advanced-editor" name="dTexto" rows="6"><?php echo $xdat['dTexto']?></textarea>
        </label>
        </div>
    </div>
<?php if($u->isSuperUser()) {?>
    <div class="row">
        <div class="small-12 columns">
        <label>URL Video:
            <input class="formaInput form-control" name="dVideo" type="url" value="<?php echo $xdat['dVideo']?>" />
        </label>
        </div>
    </div>
<?php }?>
    <div class="row mt10">
        <div class="small-12 columns">
        <label>
            <input class="formaSubmit button radius" type="submit" value="Actualizar" />
        </label>
        </div>
    </div>
    </form>
<script>
$("#forma").validate();
</script>
    
    </div>

<?php } else {?>

    <h2 class="titulo">Admin Textos<br /><a class="link" href="/_admin">Administración</a><?php if($u->isSuperUser()) {?><a class="link" href="<?php echo $this->action('agregar')?>">Agregar Texto</a><?php }?></h2>
    
    <div class="small-8 small-offset-3 column end">
    <div class="lista">
        <ul>
<?php   
$db = Loader::db();
$rq = $db->query("SELECT * FROM grTextos ORDER BY dTitulo");
while($D=$rq->fetchrow()) {
    echo'<li>'.$D['dTitulo'].' &nbsp;<a class="link" href="'.$this->action('editar',$D['dID']).'">Editar</a>';
    $I=null;
    $ri = $db->query('SELECT * FROM grTextosInformacion WHERE dID='.$D['dID'].' ORDER BY iTipo, iIndice, iTitulo');
    while($X=$ri->fetchrow()) $I[]= $X;
    if(!empty($I)) {
        echo'<a href="#" class="link" data-dropdown="drop-'.$D['dID'].'"><i class="fa fa-caret-down"></i></a>';
        echo '<ul id="drop-'.$D['dID'].'" class="tiny f-dropdown" data-dropdown-content>';
        foreach($I as $T) echo '<li><a href="'.$this->action('informacion/editar',$T['iID']).'">'.$T['iTitulo'].'</a></li>';
        echo'</ul>';
    }
    echo"</li>\n";
}
?>
        </ul>
    </div>
    </div>

<?php }?>

</div>
</div>
