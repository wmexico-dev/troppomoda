<?php defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd = $this->controller->getTask();
$u= new User();
?>

<div id="admin">
<div class="row">
    
<?php if($xcmd=='agregar' || $xcmd=='editar') {?>

    <h2 class="titulo">Admin Tienda : <?php echo ucfirst($xcmd)?> Categoría<br /><a class="link" href="/_admin/">Administración</a><a class="link" href="/_admin/tienda/">Admin Tienda</a><a class="link" href="/_admin/tienda/categorias/">Admin Categorías</a><?php if($xcmd=='editar') {?><a class="link" href="#" onClick="eliminar();return false;">Eliminar Categoría</a><?php  }?></h2> 
    
    <div class="small-8 small-offset-2 column end">
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
    <div class="row mt10">
        <div class="small-12 columns">
            <input class="formaSubmit button radius" type="submit" value="Actualizar" />
        </div>
    </div>
    </form>
    
    </div>

<?php } else {?>
    
    <h2 class="titulo">Admin Tienda : Categorías<br /><a class="link" href="/_admin/">Administración</a><a class="link" href="/_admin/tienda/">Admin Tienda</a><a class="link" href="<?php echo $this->action('agregar')?>">Nueva Categoría</a></h2>

    <div class="small-8 small-offset-2 column end">
    <div class="lista row">
        <ul>
<?php   
$db = Loader::db();
$rq = $db->query("SELECT *, (SELECT count(t.tCategoria) FROM grTienda as t WHERE t.tCategoria=c.cID) as numTienda FROM grTiendaCategorias as c ORDER BY cIndice, cNombre");
while($C=$rq->fetchrow()) if($C['cID']>100) {
    echo '<li>';
    echo $C['cNombre'];
    if(!empty($C['numTienda'])) echo ' &nbsp;<span style="font-size:12px;color:#CCCCCC">('.$C['numTienda'].')</span>';
    echo ' &nbsp;<a class="link" href="'.$this->action('editar',$C['cID']).'">Editar&nbsp;Categoría</a>';
    echo "</li>\n";
}
?>
        </ul>
    </div>
    </div>

<?php }?>

</div>
</div>
