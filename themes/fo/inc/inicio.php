<?php defined('C5_EXECUTE') or die("Access Denied.");
$xr= $c->getAttribute('Redirect');
if(!empty($xr)) if(substr($xr,0,4)=='http') {header('Location: '.$xr); exit;} else Controller::redirect($xr);
//$ver='?v=1424800000';
$ver='?v='.time();

global $c;
$xs= $c;
while ($xs->getCollectionParentID() >1) $xs= Page::getByID($xs->getCollectionParentID());
$xsh= $xs->getCollectionHandle();
$xch= $c->getCollectionHandle();
$xcn= $c->getCollectionID();
$xma=' class="active"';
$xtp= $this->getThemePath();
$u= new User();
$xga=Group::getByName('_admin');
$db= Loader::db();
Loader::library('grRegistro','gr_registro');
$xrEmail= grRegistro::email();
$xrTipo= grRegistro::tipo();
Loader::library('grCompras','gr_tienda');
$xtNumCompras = grCompras::numCompras();
?>
<!doctype html>
<html class="no-js" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://ogp.me/ns/fb#" lang="es" xml:lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta property="og:locale" content="es_LA" />
    <meta property="og:type" content="website">
    <link href='https://fonts.googleapis.com/css?family=Jaldi:700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.css"/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick-theme.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $xtp?>/css/gxnav.css<?php echo $ver?>" />
<?php if (!$c->isEditMode()) {?>
    <link rel="stylesheet" type="text/css" href="<?php echo $xtp?>/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $xtp?>/css/foundation.css" />
<?php }?>
    <link rel="stylesheet" type="text/css" href="<?php echo $xtp?>/css/sitio.css<?php echo $ver?>" />
    <link rel="stylesheet" type="text/css" media="print" href="<?php echo $xtp?>/css/print.css<?php echo $ver?>" />
    <?php Loader::element('header_required');?>
    <script src="<?php echo $xtp?>/js/vendor/modernizr.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.validation/1.14.0/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo $xtp?>/js/jquery.validate.messages_es.js"></script>
    <script type="text/javascript" src="<?php echo $xtp?>/js/superfish.js"></script>
    <script type="text/javascript" src="<?php echo $xtp?>/js/gxnav.js<?php echo $ver?>"></script>
    <script type="text/javascript" src="<?php echo $xtp?>/js/sitio.js<?php echo $ver?>"></script>
</head>
<body>
<div id="fb-root"></div>
    
<div id="inicio">
    <div style="height:10px"></div>
    <div class="row rel">
        <div class="small-12 medium-6 columns" style="padding-bottom:20px">
<div style="height:15px"></div>
<div class="logo"><img src="/files/imagenes/lg_troppo_wt.png" alt="Troppo" /></div>
        </div>
        <div class="small-12 medium-6 columns clearfix">
            <div id="inicio0" style="padding-bottom:18px">
<ul class="sfnav">
<?php if($u->isSuperUser() || $u->inGroup($xga)) echo'<li><a href="/_admin/"><i class="fa fa-pencil"></i> Admin</a></li>';?>
<?php 
    if(empty($xrEmail)) echo'<li><a href="/registro/"><i class="fa fa-user"></i> Inicio/Registro</a></li>';
    else {
        echo'<li><a href="" onClick="return false"><i class="fa fa-user"></i>  '.$xrEmail.'</a><ul><li><a href="/registro/salir/">Salir</a></li></ul></li>';
        if($xrTipo>0) echo'<li><a class="vip" href="/tienda/vip/">VIP</a></li>';
    } 
?>
</ul>
<?php if($u->isSuperUser() || !empty($xtNumCompras)) {?>
<div id="compras0">
    <a href="/tienda/compras/">COMPRAS (<?php echo $xtNumCompras?>)</a>
</div>
<? }?>
<div id="buscar0">
<form role="form" class="forma" name="buscar" method="post" action="/tienda/buscar/">
    <div class="row collapse">
        <div class="small-10 columns">
          <input type="text" name="Buscar" placeholder="Buscar artículo, marca o modelo" />
        </div>
        <div class="small-2 columns" style="float:left">
          <button></button>
        </div>
    </div>
</form>
</div>
<div>
<?php $a= new GlobalArea('Inicio'); $a->display($c)?>
</div>
            </div>
        </div>   
    </div>
</div>

<div id="menu1">
    <div class="row rel">
    <div class="small-12 columns">
    <div class="gxnav-menu"><i class="icon fa fa-bars"></i>&nbsp; Menú</div>
    <ul class="gxnav">
        <li><a href="/">Inicio</a></li>
        <li><a href="/tienda/">Catálogo</a>
<?php
$M= $db->getAll('SELECT DISTINCT c.cID,c.cIdent,c.cNombre FROM grTienda as t, grTiendaCategorias as c WHERE c.cID=t.tCategoria AND t.tCategoria>0 ORDER BY c.cNombre');
if(!empty($M)) {
    $C=null;
    function xc($X=null) {
        $r=null;
        if(!empty($X)) $r='<a href="/tienda/categoria/'.$X['cIdent'].'/">'.$X['cNombre']."</a>";
        return $r;
    }
    foreach ($M as $k=>$v) $C[$k%2][]=$v;
    echo "<div><table class=\"nofo\">\n";
    foreach ($C[0] as $k=>$v) {
        echo "<tr>";
        echo "<td>". xc($C[0][$k]) ."</td>";
        echo "<td>". xc($C[1][$k]) ."</td>";
        echo "</tr>\n";
    }
    echo "</table></div>\n";
}?>
        </li>
<?php
$M= $db->getAll('SELECT DISTINCT m.mID,m.mIdent,m.mNombre FROM grTienda as t, grTiendaMarcas as m WHERE m.mID=t.tMarca AND t.tMarca>0 ORDER BY m.mNombre');
if(!empty($M)) {
    echo'<li><a href="/tienda/marcas/">Marcas</a>';
    if(count($M)>1) {
        $C=null;
        function xa($X=null) {
            $r=null;
            if(!empty($X)) $r='<a href="/tienda/marcas/'.$X['mIdent'].'/">'.$X['mNombre']."</a>";
            return $r;
        }
        foreach ($M as $k=>$v) $C[$k%5][]=$v;
        echo "<div><table class=\"nofo\">\n";
        foreach ($C[0] as $k=>$v) {
            echo "<tr>";
            echo "<td>". xa($C[0][$k]) ."</td>";
            echo "<td>". xa($C[1][$k]) ."</td>";
            echo "<td>". xa($C[2][$k]) ."</td>";
            echo "<td>". xa($C[3][$k]) ."</td>";
            echo "<td>". xa($C[4][$k]) ."</td>";
            echo "</tr>\n";
        }
        echo "</table></div>\n";
    }
    echo'</li>';
}?>
<?php
$M= $db->getAll('SELECT DISTINCT p.pID,p.pIdent,p.pNombre FROM grTienda as t, grTiendaPromociones as p WHERE p.pID=t.tPromocion AND t.tPromocion>0 ORDER BY p.pNombre');
if(!empty($M)) {
    echo'<li><a href="/tienda/promociones/">Promociones</a>';
    if(count($M)>1) {
        echo'<ul>';//<li><a href="/tienda/promociones/">Todas las promociones</a></li>';
        foreach($M as $X) echo'<li><a href="/tienda/promociones/'.$X['pIdent'].'/">'.$X['pNombre']."</a></li>";
        echo'</ul>';
    }
    echo'</li>';
}?>
        <li><a href="/tienda/novedades/">Novedades</a></li>
        <li><a href="/como-comprar/">Cómo comprar</a></li>
        <li><a href="/nosotros/">Nosotros</a></li>
        <li><a href="/contacto/">Contacto</a></li>
    </ul>
    </div>
    </div>
    <div class="sombra"></div>
</div>

<?php 
    Loader::model('grImagenes','gr_imagenes');
    $I= grImagenes::imagenes($xsh);
    if(!empty($I)) {
//        echo "<div>".var_dump($I)."/div>\n";
        shuffle($I);?>
<div id="imagenes">
    <div class="row rel"><div id="imagenes-a"></div></div>
    <div id="imagenes-s" style="display:none">
<?php foreach ($I as $D) {
    echo'<div>';
    if(!empty($D['iLink'])) echo'<a href="'.$D['iLink'].'">';
    echo'<img src="/files/imagenes/i'.$D['iImagen'].'.jpg" alt="" />';
    if(!empty($D['iLink'])) echo'</a>';
    echo"</div>\n";
}?>
    </div>
</div>
<script type="text/javascript" src="/packages/gr_imagenes/js/imagenes.js<?php echo $ver?>"></script>
<?php }?>
<div class="espacio"></div>

