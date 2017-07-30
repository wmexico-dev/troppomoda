<?php defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd= $this->controller->getTask();
if(empty($ximg)) $ximg= 0;
$db= Loader::db();
?>

<div id="admin">
<div class="row">

<?php if($xcmd=='agregar' || $xcmd=='editar') {?>

    <div class="small-12 columns">
    <h2 class="titulo">Admin Tienda : Información<br /><a class="link" href="/_admin">Administración</a><a class="link" href="/_admin/tienda/">Admin Tienda</a><?php if($xcmd=='editar') {?><a class="link" href="#" onClick="eliminar();return false;">Eliminar Artículo</a><?php  }?></h2>
    </end>
    
    <div class="small-8 small-offset-1 column end">
    <?php if(is_array($xerr)) { echo '<div class="error"><ul>'; foreach ($xerr as $e) echo'<li>'.$e.'</li>'; echo "</ul></div>\n"; }?>

    <form role="form" class="forma" name="forma" method="post" action="<?php echo $this->action('actualizar')?>" enctype="multipart/form-data">
    <input name="xcmd" type="hidden" value="<?php echo $xcmd?>" /><input name="tID" type="hidden" value="<?php echo $xdat['tID']?>" /><input name="tPrecios" type="hidden" value="<?php echo $xdat['tPrecios']?>" /><input name="tImagenes" type="hidden" value="<?php echo $xdat['tImagenes']?>" />
    <div class="row">
        <div class="small-12 columns">
        <label>Nombre del Artículo:
            <input class="formaInput" name="tNombre" type="text" value="<?php echo $xdat['tNombre']?>" />
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Publicación de Artículo:
            <select name="tTipo">
                <option value="0">Normal</option>
                <option value="1" class="bold"<?php if($xdat['tTipo']==1) echo' selected'?>>Sólo para VIP</option>
                <option value="-1"<?php if($xdat['tTipo']<0) echo' selected'?>>Deshabilitada</option>
            </select>
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Modelo:
            <input class="formaInput" name="tModelo" type="text" value="<?php echo $xdat['tModelo']?>" />
        </label>
        </div>
    </div>
<?php
    $Cat= null;
    $rq= $db->query("SELECT * FROM grTiendaCategorias WHERE cID>100 ORDER BY cIndice, cNombre");
    while($rx=$rq->fetchrow()) $Cat[]=$rx;
    if(!empty($Cat)) {
?>
    <div class="row">
        <div class="small-12 columns">
        <label>Categoría:
            <select name="tCategoria" class="formaSelect">
                <option value="0">Sin Categoría</option>
<?php if(!empty($Cat)) foreach($Cat as $x) { echo '<option value="'.$x['cID'].'"'; if($x['cID']==$xdat['tCategoria']) echo ' selected'; echo '>'.$x['cNombre']."</option>\n"; }?>
            </select>
        </label>
        </div>
    </div>
<?php  }?>
<?php
    $Marca= null;
    $rq= $db->query("SELECT * FROM grTiendaMarcas WHERE mID>100 ORDER BY mIndice, mNombre");
    while($rx=$rq->fetchrow()) $Marca[]=$rx;
?>
    <div class="row">
        <div class="small-12 columns">
        <label>Marca:
            <select name="tMarca" class="formaSelect">
                <option value="0">Sin Marca</option>
<?php if(!empty($Marca)) foreach($Marca as $x) { echo '<option value="'.$x['mID'].'"'; if($x['mID']==$xdat['tMarca']) echo ' selected'; echo '>'.$x['mNombre']."</option>\n"; }?>
            </select>
        </label>
        </div>
    </div>
<?php
    $Promo= null;
    $rq= $db->query("SELECT * FROM grTiendaPromociones WHERE pID>100 ORDER BY pIndice, pNombre");
    while($rx=$rq->fetchrow()) $Promo[]=$rx;
?>
    <div class="row">
        <div class="small-12 columns">
        <label>Promoción:
            <select name="tPromocion" class="formaSelect">
                <option value="0">Sin Promoción</option>
<?php if(!empty($Promo)) foreach($Promo as $x) { echo '<option value="'.$x['pID'].'"'; if($x['pID']==$xdat['tPromocion']) echo ' selected'; echo '>'.$x['pNombre']."</option>\n"; }?>
            </select>
        </label>
        </div>
    </div>
<?php
    $Precios=null;
    if(!empty($xdat['tPrecios'])) {
        $Precios= json_decode($xdat['tPrecios'],true);
        if(!is_array($Precios)) $Precios=null;
    }
    if(empty($Precios)) $Precios= array(array('precio'=>'','promo'=>'','info'=>''));
?>
    <div class="row">
        <div class="small-12 columns">
            <table id="dtPrecios" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Precio</th>
                    <th>Precio Promoción</th>
                    <th>Unidades/Notas</th>
                </tr>
            </thead>
            <tbody>
<?php foreach($Precios as $k=>$P){?>
                <tr>
                    <td><input name="Precios[<?php echo $k?>][precio]" type="text" value="<?php echo $P['precio']?>" /></td>
                    <td><input name="Precios[<?php echo $k?>][promo]" type="text" value="<?php echo $P['promo']?>" /></td>
                    <td><input name="Precios[<?php echo $k?>][info]" type="text" value="<?php echo $P['info']?>" /></td>
                </tr>
<?php }?>
            </tbody>
            </table>
        </div>
    </div>
    <div class="row" style="margin-top:1rem">
        <div class="small-12 columns">
        <div class="panel">
        <h6>Descuento VIP en Artículo</h6>
        <div class="row">
            <div class="small-12 columns">
            <input id="tVipStatus" name="tVipStatus" type="checkbox" value="1"<?php if(!empty($xdat['tVipStatus'])) echo' checked'?>><label for="tVipStatus">Activar descuento VIP</label>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
            <label>Porcentaje de Descuento:
                <input class="formaInput" name="tVipDescuento" type="text" value="<?php echo $xdat['tVipDescuento']?>" />
            </label>
            </div>
        </div>
        </div>
        </div>
    </div>
<?php
    $Imagenes=null;
    if(!empty($xdat['tImagenes'])) {
        $Imagenes= json_decode($xdat['tImagenes'],true);
        if(!is_array($Imagenes)) $Imagenes=null;
    }
    if(empty($Imagenes)) $Imagenes= array(array('imagen'=>'','principal'=>'1'));
?>
    <div class="row mt10">
        <div class="small-12 columns">
            <table id="dtImagenes" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Imagen Actual</th>
                    <th>Imagen a publicar</th>
                    <th>Principal</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
<?php foreach($Imagenes as $k=>$I){?>
                <tr>
                    <td><div class="imagen"><?php if(!empty($I['imagen'])) echo '<img src="/files/tienda/a'.$I['imagen'].'.jpg?t='.time().'" alt="" /><input name="Imagenes['.$k.'][imagen]" type="hidden" value="'.$I['imagen'].'" />'?></div></td>
                    <td><div class="upload button tiny radius"><span>Buscar archivo</span><input name="ImagenArchivo[<?php echo $k?>]" type="file" /></div><?php echo '<input name="Imagenes['.$k.'][n]" type="hidden" value="'.$k.'" />'?></td>
                    <td><input name="ImagenPrincipal" type="radio" value="<?php echo $k?>" <?php if(!empty($I['principal'])) echo'checked="checked" '?>/></td>
                    <td><input name="Imagenes[<?php echo $k?>][eliminar]" type="checkbox" value="1" /></td>
                </tr>
<?php }?>
            </tbody>
            </table>
        </div>
    </div>
    <div class="row mt10">
        <div class="small-12 columns">
        <label>Palabras Clave:
            <input class="formaInput" name="tClaves" type="text" value="<?php echo $xdat['tClaves']?>" />
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Descripción:
            <textarea class="formaArea ccm-advanced-editor" name="tDescripcion" rows="7"><?php echo $xdat['tDescripcion']?></textarea>
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

<?php } elseif($xcmd=='vip') {?>
       
    <div class="small-12 columns">
    <h2 class="titulo">Admin Tienda : Editar Descuento VIP<br /><a class="link" href="/_admin/tienda/">Admin Tienda</a><a class="link" href="/_admin">Administración</a></h2>
    </div>
    
    <div class="medium-6 medium-offset-3 small-10 small-offset-1 column end">
    <?php if(is_array($xerr)) { echo '<div class="error"><ul>'; foreach ($xerr as $e) echo'<li>'.$e.'</li>'; echo "</ul></div>\n"; }?>
    
    <h4>Descuento VIP General</h4>
    <form role="form" class="forma" id="forma" name="forma" method="post" action="<?php echo $this->action('vip')?>">
    <input name="xcmd" type="hidden" value="<?php echo $xcmd?>" />
    
    <div class="row">
        <div class="small-12 columns">
        <input id="tVipStatus" name="tVipStatus" type="checkbox" value="1"<?php if(!empty($xdat['tVipStatus'])) echo' checked'?>><label for="tVipStatus">Activar descuento VIP</label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Porcentaje de Descuento:
            <input class="formaInput" name="tVipDescuento" type="text" value="<?php echo $xdat['tVipDescuento']?>" />
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
    
    
<?php } else {?>

    <div class="small-12 columns">
    <h2 class="titulo">Admin Tienda<br /><a class="link" href="/_admin">Administración</a><a class="link" href="<?php echo $this->action('agregar')?>">Agregar Artículo</a></h2>
    </div>
    
    <div class="small-9 small-offset-1 column end">
    <div class="lista">
    <?php  
        $db= Loader::db();
        $rq= $db->query('SELECT * FROM grTienda as t, grTiendaCategorias as c, grTiendaMarcas as m, grTiendaPromociones as p WHERE c.cID=t.tCategoria AND m.mID=t.tMarca AND p.pID=t.tPromocion ORDER BY cNombre, tNombre'); 
        $cc= -1;
        while($L=$rq->fetchrow()) {
            if($L['cID']!=$cc) {
                if($cc>-1) echo "</ul>\n";
                if(empty($L['cID'])) $nom= "Sin Categoría"; else $nom= $L['cNombre'];
                echo '<h4>'.$nom."</h4>\n<ul class=\"clearfix\">\n";
                $cc= $L['cID'];
            }
            echo '<li>';
            echo $L['tNombre'];
            if(!empty($L['tModelo'])) echo ' &middot; '.$L['tModelo'];
            if(!empty($L['tImagen'])) echo ' &nbsp; <img src="/files/tienda/a'.$L['tImagen'].'.jpg?v='.time().'" style="max-height:22px" alt="" />';
            echo ' &nbsp;<a class="link" href="'.$this->action('editar',$L['tID']).'">Editar</a>';
            echo '&nbsp;<a class="link" href="/tienda/detalle/'.$L['tID'].'">Ver</a>';
            if(!empty($L['tPromocion'])) echo ' &nbsp; <span style="font-size:11px;color:'.$L['pColor'].'">'.$L['pNombre'].'</span>';
            if(!empty($L['tVipStatus'])) echo' &nbsp; <span style="font-size:11px;color:#993333">-<i class="fa fa-percent"></i>VIP</span>';
            if(!empty($L['tTipo'])){ echo ' &nbsp; <span style="font-size:11px;color:#333333"><i class="fa fa-eye"></i> '; if($L['tTipo']>0) echo'Sólo VIP'; else echo'No se ve'; echo'</span>'; }
            echo "</li>\n";
        }
        echo "</ul>\n";
    ?>
    </div>
    </div>

<?php }?>

</div>
</div>
