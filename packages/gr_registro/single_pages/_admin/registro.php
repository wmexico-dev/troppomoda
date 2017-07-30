<?php defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd= $this->controller->getTask();
$db= Loader::db();
?>

<div id="admin">
<div class="row">

<?php if($xcmd=='clave') {?>

    <div class="small-12 columns">
    <h2 class="titulo">Admin Registro : Cambiar Clave<br /><a class="link" href="/_admin/registro/">Admin Registro</a><a class="link" href="/_admin">Administración</a></h2>
    </div>
    
    <div class="medium-6 medium-offset-3 small-10 small-offset-1 column end">
    <?php if(is_array($xerr)) { echo '<div class="error"><ul>'; foreach ($xerr as $e) echo'<li>'.$e.'</li>'; echo "</ul></div>\n"; }?>
    <p class="gris">Al cambiar esta clave es sólo para el acceso a nuevos usuarios,<br />los usuarios ya registrados mantienen su contraseña.</p>
    <form role="form" class="forma" id="forma" name="forma" method="post" action="<?php echo $this->action('clave')?>" enctype="multipart/form-data">
    <input name="xcmd" type="hidden" value="<?php echo $xcmd?>" />
    <div class="row">
        <div class="small-12 columns">
        <label>Nueva Clave general:
            <input class="formaInput" name="rClave" type="text" value="" required />
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
<script>
$("#forma").validate();
</script>
    </div>
    
<?php } elseif($xcmd=='editar') {?>
    
    <div class="small-12 columns">
    <h2 class="titulo">Admin Registro : Editar un Registro<br /><a class="link" href="/_admin/registro/editar">Admin Registro</a><a class="link" href="/_admin">Administración</a></h2>
    </div>
    
    <div class="medium-6 medium-offset-3 small-10 small-offset-1 column end">
    <?php if(is_array($xerr)) { echo '<div class="error"><ul>'; foreach ($xerr as $e) echo'<li>'.$e.'</li>'; echo "</ul></div>\n"; }?>
    
    <form role="form" class="forma" id="forma" name="forma" method="post" action="<?php echo $this->action('editar')?>">
    <input name="xcmd" type="hidden" value="<?php echo $xcmd?>" />
    <div class="row">
        <div class="small-12 columns">
        <label>E-mail/Correo del usuario registrado a editar:
            <input class="formaInput" name="rEmail" type="email" value="" required />
        </label>
        </div>
    </div>
    <div class="row mt10">
        <div class="small-12 columns">
        <label>
            <input class="formaSubmit button radius" type="submit" value="Editar" />
        </label>
        </div>
    </div>
    </form>
<script>
$("#forma").validate();
</script>
    </div>
    
    <div class="small-9 small-offset-2 column end">
    <div class="lista">
<?php   
$db = Loader::db();
$rq = $db->query("SELECT * FROM grRegistro WHERE rTipo>0 ORDER BY rEmail");
$xvip=false;
while($C=$rq->fetchrow()){
    if(empty($xvip)){$xvip=true; echo"<h4>Registros VIP</h4>\n<ul>\n";}
    echo '<li>';
    echo $C['rEmail'];
    if(!empty($C['rVipFin'])) echo ' &nbsp; <span style="font-size:11px;color:#555">Vence en '.date('Y-m-d',$C['rVipFin']).'</span>';
    echo ' &nbsp;<a class="link" href="'.$this->action('editarRegistro',$C['rID']).'">Editar&nbsp;Registro</a>';
    echo "</li>\n";
}
if(empty($xvip)) echo"<h4 style=\"color:#660000\">No tenemos registros VIP</h4>\n"; else echo"</ul>\n";
?>
    </div>
    </div>
    
    <div class="small-9 small-offset-2 column end">
    <div class="lista">
        
<?php   
$db = Loader::db();
$rq = $db->query("SELECT * FROM grRegistro WHERE rTipo<0 ORDER BY rEmail");
$xvip=false;
while($C=$rq->fetchrow()){
    if(empty($xvip)){$xvip=true; echo"<h4>Registros Deshabilitados</h4>\n<ul>\n";}
    echo '<li>';
    echo $C['rEmail'];
    echo ' &nbsp;<a class="link" href="'.$this->action('editarRegistro',$C['rID']).'">Editar&nbsp;Registro</a>';
    echo "</li>\n";
}
if(empty($xvip)) echo"<h4 style=\"color:#660000\">No tenemos registros Deshabilitados</h4>\n"; else echo"</ul>\n";
?>
        
    </div>
    </div>

<?php } elseif($xcmd=='editarRegistro') {?>
    
    <div class="small-12 columns">
    <h2 class="titulo">Admin Registro : Editando Registro<br /><a class="link" href="/_admin/registro/editar">Admin Registro</a><a class="link" href="/_admin">Administración</a><a id="eliminar" class="link">Eliminar Registro</a></h2>
    </div>
    
    <div class="medium-6 medium-offset-3 small-10 small-offset-1 column end">
    <?php if(is_array($xerr)) { echo '<div class="error"><ul>'; foreach ($xerr as $e) echo'<li>'.$e.'</li>'; echo "</ul></div>\n"; }?>
    
    <form role="form" class="forma" name="forma" method="post" action="<?php echo $this->action('actualizar')?>">
    <input name="xcmd" type="hidden" value="<?php echo $xcmd?>" />
    <input name="rID" type="hidden" value="<?php echo $xdat['rID']?>" />
    <input name="rVipFin" type="hidden" value="<?php echo $xdat['rVipFin']?>" />
    <input name="xVipFinS" type="hidden" value="" />
<?php 
    if(empty($xdat['rVipFin'])){ $tVipFin= time() +(30*24*60*60);
        echo "<script>\nvar xvf=false;\n</script>\n"; }
    else { $tVipFin=$xdat['rVipFin'];
        echo "<script>\nvar xvf=true;\n</script>\n"; }
?>
    <div class="row">
        <div class="small-12 columns">
        <div class="xlabel" style="margin-bottom:20px">E-mail/Correo del usuario registrado:<br />&nbsp; <span class="bold fs16"><?php echo $xdat['rEmail']?></span></div>
        <div class="xlabel" style="margin-bottom:20px">Registrado desde:&nbsp; <?php echo date('Y-m-d H:i:s',$xdat['rID'])?><br/>
        <?php
            if(!empty($xdat['rLogin'])) echo"Último Acceso:&nbsp; ".date('Y-m-d H:i:s',$xdat['rLogin'])."<br />\n";
            $rq = $db->query("SELECT count(*) as cNum, cID FROM grRegistroCompras WHERE rID=".$xdat['rID']." ORDER BY cID DESC");
            if($rx=$rq->fetchrow()) if(empty($rx['cNum'])) echo "No tiene órdenes de compra registradas";
                else {
                echo "Última orden de compra:&nbsp; ".date('Y-m-d H:i:s',$rx['cID'])."<br />\n";
                echo "Número de órdenes de compra registradas: &nbsp; ".$rx['cNum'];
                } 
        ?>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Tipo de usuario:
            <select name="rTipo">
                <option value="0">Normal</option>
                <option value="1" class="bold"<?php if($xdat['rTipo']==1) echo' selected'?>>VIP</option>
                <option value="-1"<?php if($xdat['rTipo']<0) echo' selected'?>>Deshabilitado</option>
            </select>
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <div class="xlabel" style="margin-bottom:20px">Fecha de vencimiento VIP:
            <div id="xVipFin0" style="padding-left:10px">
                No se ha asignado fecha de vencimiento. <a id="xVipFinB1" class="link">Asignar fecha</a>
            </div>
            <div id="xVipFin1">
                <input id="xVipFin" name="xVipFin" style="margin-bottom:6px" value="<?php echo date('Y-m-d',$tVipFin)?>">
                <a id="xVipFinB0" class="link">Borrar fecha</a>
            </div>
        </div>    
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Notas VIP:
            <textarea class="formaArea form-control" name="rVipInfo" rows="3"><?php echo $xdat['rVipInfo']?></textarea>
        </label>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
        <label>Información capturada:
            <div class="fs11" style="padding-left:11px">
                <pre>
<?php
if(empty($xdat['rInfo'])) echo '<span style="color:red">No tenemos información de este Registro.</span>';
else {
    $Info = json_decode($xdat['rInfo'],true);
    foreach($Info as $l=>$v){
        if(substr($l,0,1)!='x') echo '['.$l.'] : '.$v."\n";
    }
}?>
                </pre>
            </div>
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
<script>
$("#forma").validate();
</script>
    </div>
    
<?php } else {?>
    
    <div class="small-12 columns">
    <h2 class="titulo">Admin Registro<br /><a class="link" href="/_admin">Administración</a></h2>
    </div>

    <div class="small-8 small-offset-2 column end">
    <ul>
        <li><a href="/_admin/registro/clave/">Cambiar Clave general</a></li>
        <li><a href="/_admin/registro/editar/">Administrar Registros (usuarios)</a></li>
    </ul>
    </div>
    
<?php }?>

</div>
</div>