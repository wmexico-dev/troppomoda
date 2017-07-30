<?php  defined('C5_EXECUTE') or die(_("Access Denied."));
$db= Loader::db();
$rq= $db->query("SELECT * FROM grTienda WHERE tID=0");
if (!($rx=$rq->fetchrow())) $this->redirect('/?e=tienda_noid_base');
$DescVipStatus= $rx['tVipStatus'];
?> 

<div id="admin">
<div class="row">
    <div class="small-12 columns">
    <h2 class="titulo">Menú de Administración<br /><a class="link" href="<?php echo $this->url('/login/logout')?>">Salir Administrar</a></h2>
    </div>
    
    <div class="small-8 small-offset-2 column end">
    <ul>
        <li><a href="/_admin/tienda/">Administrar Tienda : Artículos</a></li>
        <li><a href="/_admin/tienda/categorias/">Administrar Tienda : Categorías</a></li>
        <li><a href="/_admin/tienda/marcas/">Administrar Tienda : Marcas</a></li>
        <li><a href="/_admin/tienda/promociones/">Administrar Tienda : Promociones</a></li>
        <li><a href="/_admin/tienda/vip/">Administrar Tienda : Descuento VIP</a><?php if(!empty($DescVipStatus)) echo' &nbsp; <span style="font-size:11px;color:#993333">-<i class="fa fa-percent"></i>VIP</span>'?></li>
        <li><a href="/_admin/registro/editar/">Administrar Registro</a></li>
        <li><a href="/_admin/imagenes/">Administrar Imágenes</a></li>
        <li><a href="/_admin/textos/">Administrar Textos</a></li>
    </ul>
    </div>
</div>
</div>

<div id="central">        
    <?php  $a = new Area('Central'); $a->display($c);?>     
</div>
