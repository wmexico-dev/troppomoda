<?php defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd = $this->controller->getTask();
?>

<div id="admin">
<div class="row">
    
<?php if($xcmd=='agregar' || $xcmd=='editar') {?>

    <div class="small-12 columns">
    <h2 class="titulo">Admin Imágenes : <?php echo ucfirst($xcmd)?> Categoría<br /><a class="link" href="/_admin/">Administración</a><a class="link" href="/_admin/imagenes/categorias/">Admin Categorías</a><?php if($xcmd=='editar') {?><a class="link" href="#" onClick="eliminar();return false;">Eliminar Categoría</a><?php }?></h2>
    </div>
    
    <div class="small-6 small-offset-3 column end">
    <?php if(is_array($xerr)) { echo '<div class="error"><ul>'; foreach ($xerr as $e) echo'<li>'.$e.'</li>'; echo "</ul></div>\n"; }?>

    <form role="form" class="forma" name="forma" method="post" action="<?php echo $this->action('actualizar')?>">
    <input type="hidden" name="xcmd" value="<?php echo $xcmd?>" /><input type="hidden" name="cID" value="<?php echo $xdat['cID']?>" />
    <div class="row">
        <div class="small-12 columns">
        <label>Nombre&nbsp;Categor&iacute;a:
            <input class="formaInput form-control" name="cNombre" type="text" value="<?php echo $xdat['cNombre']?>" />
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Identificación:
            <input class="formaInput form-control" name="cIdent" type="text" value="<?php echo $xdat['cIdent']?>" />
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>MaxAncho:
            <input class="formaInput form-control" name="cAncho" type="text" value="<?php echo $xdat['cMaxAncho']?>" />
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>MaxAltura:
            <input class="formaInput form-control" name="cAltura" type="text" value="<?php echo $xdat['cMaxAltura']?>" />
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Style:  &nbsp;(opcional)
            <input class="formaInput form-control" name="cStyle" type="text" value="<?php echo $xdat['cStyle']?>" />
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Nota:
            <input class="formaInput form-control" name="cNota" type="text" value="<?php echo $xdat['cNota']?>" />
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
    
    <h2 class="titulo">Admin Imágenes : Categorías<br /><a class="link" href="/_admin/">Administración</a><a class="link" href="/_admin/imagenes/">Admin Imágenes</a><a class="link" href="<?php echo $this->action('agregar')?>">Nueva Categoría</a></h2>
    
    <div class="small-6 small-offset-3 column end">
    <div class="lista">
        <ul>
<?php   
$db = Loader::db();
$rq = $db->query("SELECT *, (SELECT count(d.cID) FROM grImagenes as d WHERE d.cID=c.cID) as numImagenes FROM grImagenesCategorias as c");
while($C=$rq->fetchrow()) {
    echo '<li>'.$C['cNombre'];
    echo ' &nbsp;<span style="font-size:12px;color:#CCCCCC">('.$C['numImagenes'].')</span>';
    if(!empty($C['cID'])) echo ' &nbsp;<a class="link" href="'.$this->action('editar',$C['cID']).'">Editar&nbsp;Categoría</a>';
    echo "</li>\n";
}
?>
        </ul>
    </div>
    </div>

<?php }?>

</div>
</div>
