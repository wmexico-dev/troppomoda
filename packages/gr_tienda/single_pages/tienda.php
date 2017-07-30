<?php defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd = $this->controller->getTask();
global $c;
$u= new User();
$xga=Group::getByName('_admin');
$db = Loader::db();
Loader::library('grRegistro','gr_registro');
$xrEmail= grRegistro::email();
$xrTipo= grRegistro::tipo();
$ver='?v='.time();
$rq= $db->query("SELECT * FROM grTienda WHERE tID=0");
if(!($rx=$rq->fetchrow())) $this->redirect("/?e=tienda_no_id0");
$vipDesc=0;
if(!empty($rx['tVipStatus'])) $vipDesc= $rx['tVipDescuento'];
if(!empty($xdat['mVipStatus'])) $vipDesc= $xdat['mVipDescuento'];
if(!empty($xdat['pVipStatus'])) $vipDesc= $xdat['pVipDescuento'];
if(!empty($xdat['tVipStatus'])) $vipDesc= $xdat['tVipDescuento'];
$descuentoV= 1.0-(floatval($vipDesc)/100);
?>

<?php if($xcmd=='detalle') {?>

<div class="tienda detalle">
<div class="row">
    <div class="small-12 column">
        <h2 class="tit-seccion">DETALLE : <?php echo $xdat['tNombre']?></h2>
    </div>
    <div class="small-12 medium-7 column">
<?php if(!empty($xdat['tModelo'])) echo '<p><strong>MODELO:&nbsp; </strong>'.$xdat['tModelo']."</p>\n"?> 
<?php if(!empty($xdat['tMarca'])) echo '<p><strong>MARCA:&nbsp; </strong>'.$xdat['mNombre']."</p>\n"?>
<?php if(!empty($xdat['tDescripcion'])) {?>
        <p style="margin-top:15px"><strong>DESCRIPCIÓN:</strong></p>
<?php echo $xdat['tDescripcion'];
}?>
        <p style="margin-top:15px"><strong>PRECIOS:</strong></p>
<?php 
if(empty($xrEmail)) echo '<p>Para poder ver los precios es necesario que se registre (<a href="/registro/">aquí</a>).</p>';
else {
    $Precios=null;
    if(!empty($xdat['tPrecios'])) {
        $Precios= json_decode($xdat['tPrecios'],true);
        if(!is_array($Precios)) $Precios=null;
    }
    if(empty($Precios)) echo'<p>No hay precios publicados para este Artículo.</p>';
    else {
        if(count($Precios)==1) $uno=true;
        echo'<form role="form" class="comprar" method="post" action="/tienda/compras/o/comprar/">'."\n";
        foreach($Precios as $k=>$P){
            echo'<p><input type="radio" name="id" value="'.$k.'" id="'.$k.'"';
            if(!empty($uno)) echo' checked="checked"';
            echo' /><label for="'.$k.'">&nbsp;';
            $p= $P['precio'];
            if($xrTipo>0) $p*= $descuentoV;
            if(!empty($xdat['tPromocion']) && !empty($P['promo'])){
                $pp= $P['promo'];
                if($xrTipo>0) $pp*= $descuentoV;
                echo'<span class="p-no">$'.number_format(round($p),2).'</span> &nbsp;<span class="promo">$'.number_format(round($pp),2).'</span>';
            } else {
                echo '<span class="precio">$ '.number_format(round($p),2).'</span>';
            }
            echo'&nbsp; '.$P['info']."</label></p>\n";
        }
        echo"<button style=\"margin-top:20px\" class=\"button radius\">Comprar</button>\n</form>\n";
        
    }
}
if($u->isSuperUser() || $u->inGroup($xga)){
    echo"<div id=\"admin\" style=\"margin-top:15px\">\n<p><strong>Descuento VIP:</strong></p>\n";
    echo"<p>Porcentaje de descuento que se aplicó : <strong>". number_format(floatval($vipDesc),2);
    echo"</strong><br />\n<span class=\"fs11\">Descuento por Artículo : ";
    if(empty($xdat['tVipStatus'])) echo"No"; else echo number_format(floatval($xdat['tVipDescuento']),2);
    echo"</span><br />\n<span class=\"fs11\">Descuento por Promoción : ";
    if(empty($xdat['pVipStatus'])) echo"No"; else echo number_format(floatval($xdat['pVipDescuento']),2);
    echo"</span><br />\n<span class=\"fs11\">Descuento por Marca : ";
    if(empty($xdat['mVipStatus'])) echo"No"; else echo number_format(floatval($xdat['mVipDescuento']),2);
    echo"</span><br />\n<span class=\"fs11\">Descuento VIP general : ";
    if(empty($rx['tVipStatus'])) echo"No"; else echo number_format(floatval($rx['tVipDescuento']),2);
    echo"</span></p>\n";
    echo"<p>&nbsp;</p>\n<p><a class=\"link\" href=\"/_admin/tienda/editar/".$xdat['tID']."/\">Editar Artículo</a></p>\n</div>\n";
    echo"<p>&nbsp;</p>\n";
}
?>

    </div>
    <div class="small-12 medium-5 column">
        <div id="d-imagen" class="d-img">
        <a href="/files/tienda/o<?php if(!empty($xdat['tImagen'])) echo $xdat['tImagen']?>.jpg" class="fzoom">
            <img src="/files/tienda/o<?php if(!empty($xdat['tImagen'])) echo $xdat['tImagen']?>.jpg" alt="" />
        </a>
        </div>
<?php
    $N= null;
    if(!empty($xdat['tImagenes'])) $N= json_decode($xdat['tImagenes'],true);
    if(is_array($N)) if(count($N)>1) {
        shuffle($N);
?>
    <div id="d-imagenes" style="display:none">
<?php   foreach($N as $I) {?>
        <div class="t-slide" style="margin:2px 10px">
        <div class="t-item">
<div class="t-img">
    <img src="<?php echo'/files/tienda/a'.$I['imagen'].'.jpg'.$ver?>" alt="" \>
    <a href="/files/tienda/o<?php echo $I['imagen']?>.jpg"></a>
</div>
        </div>
        </div>
<?php }?>  
    </div>
<?php }?>
    </div>
</div>
<div class="espacio"></div>
</div>
    
<?php } else {
    $xtitulo='CATÁLOGO';
    if($xcmd=='promociones') $xtitulo='PROMOCIONES';
    if($xcmd=='novedades') $xtitulo='NOVEDADES';
    if($xcmd=='buscar') $xtitulo='BÚSQUEDA';
    if(!empty($xtit)) $xtitulo.=' : '.$xtit;
    
    if(!empty($_GET['pg'])) $xpag= intval($_GET['pg']);
    $xnumpp= 20;
    $xnumtotal= count($xdat);
    $xnumpags= floor($xnumtotal / $xnumpp);
    if($xnumpags>0 AND ($xnumtotal-($xnumpags*$xnumpp)) <5) $xnumpags -= 1;
    if(empty($xpag) || !is_numeric($xpag)) $xpag=0;
    if($xpag>$xnumpags) $xpag=$xnumpags;
    $xnumi= $xpag *$xnumpp;
    $xnumf= (($xpag +1) *$xnumpp) -1;
    if($xpag==$xnumpags) $xnumf= $xnumtotal -1;
    
//    echo '<div>'.$xnumtotal.':'.$xnumpags.':'.$xpag.':'.$xnumi.':'.$xnumf.'</div>';
?> 

<div class="tienda">
<div class="row">
    <div class="small-12 column">
        <h2 class="tit-seccion">
<?php 
echo $xtitulo;
if($xnumpags>0) {
    echo ' &nbsp;<span class="fs15">(Página '.($xpag +1).' de '.($xnumpags +1).')';
    if($xpag<$xnumpags) echo ' &nbsp;<a href=".?pg='.($xpag +1).'"><i class="fa fa-caret-right"></i></a>';
    echo '</span>';
}
?>
        </h2>
<?php if($xcmd=='buscar') if(empty($xdat)) echo'<p><strong>No</strong> se encontraron resultados en la búsqueda: '.$xbuscar.'</p>';
            else echo'<p>Se encontraron <strong>'.count($xdat).'</strong> resultados en la búsqueda: '.$xbuscar.'</p>';
?>
        <ul class="small-block-grid-2 medium-block-grid-3 large-block-grid-4">
<?php if(!empty($xdat) && is_array($xdat)) {
    for($xn=$xnumi;$xn<=$xnumf;$xn +=1) {
    $D= $xdat[$xn];
?>
<li class="t-item">
<div class="t-img">
    <img src="<?php echo'/files/tienda/a'.$D['tImagen'].'.jpg'.$ver?>" alt="<?php echo $D['tNombre']?>" \>
    <?php if(!empty($D['tPromocion'])) echo '<div class="t-promo" style="background-color:'.$D['pColor'].'">'.$D['pNombre'].'</div>'?>
    <a href="<?php echo'/tienda/detalle/'.$D['tID']?>"><?php echo $D['tNombre']?></a>
</div>
<div class="t-blk">
<?php echo '<a class="t-nom" href="/tienda/detalle/'.$D['tID'].'">'.$D['tNombre'].'</a>';
if(!empty($D['tModelo'])) echo '<div class="t-info">Modelo: '.$D['tModelo'].'</div>';
if(!empty($D['tMarca'])) echo '<div class="t-info">Marca: '.$D['mNombre'].'</div>'; ?>
    <a class="t-btn button radius" href="/tienda/detalle/<?php echo $D['tID']?>">DETALLE</a>
</div>
</li>
<?php }}?>
        </ul>
    </div>
</div>

<?php if($xnumpags>0) {?>
<div id="numpaginas" class="row">
    <div class="small-12 column centro bold">
<?php 
echo 'Páginas: ';
for($p=0;$p<=$xnumpags;$p +=1) {
    if($p==$xpag) echo '<span style="color:#666666">'.($p +1).'</span> '; 
    else echo '<span><a href=".?pg='.$p.'">'.($p +1).'</a></span> ';
}
?>
    </div>
</div>
<?php }?>

</div>
<?php }?>
