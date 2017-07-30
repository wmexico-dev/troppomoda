<?php defined('C5_EXECUTE') or die(_("Access Denied."));
$this->inc('inc/inicio.php');
global $c;
$xcn= $c->getCollectionID();
?> 

<div id="contenido">
    
<?php if($xcn==1) {
    $db= Loader::db();
    Loader::model('grImagenes','gr_imagenes');
    
    $I= grImagenes::imagenes('principal-b01');
    if(!empty($I)) {
//        echo "<div>".var_dump($I)."/div>\n";
        shuffle($I);
?>
<div id="anuncios">
<div id="principal-b01" class="b-anuncios">
    <div class="row">
        <div class="small-12 medium-6 columns">
            <div class="b-img">
<img src="/files/imagenes/i<?php echo $I[0]['iImagen']?>.jpg" alt="" />
<?php if(!empty($I[0]['iLink'])) echo'<a href="'.$I[0]['iLink']."\"></a>"?>             
            </div>
        </div>
<?php if(!empty($I[1])){?>
        <div class="small-12 medium-6 columns">
            <div class="b-img">
<img src="/files/imagenes/i<?php echo $I[1]['iImagen']?>.jpg" alt="" />
<?php if(!empty($I[1]['iLink'])) echo'<a href="'.$I[1]['iLink']."\"></a>"?>             
            </div>
        </div>
<?php }?>
    </div>
</div>
<?php }
    $I= grImagenes::imagenes('principal-b02');
    if(!empty($I)) {
        shuffle($I);
?>
<div id="principal-b02" class="b-anuncios">
    <div class="row">
        <div class="small-12 medium-4 columns">
            <div class="b-img">
<img src="/files/imagenes/i<?php echo $I[0]['iImagen']?>.jpg" alt="" />
<?php if(!empty($I[0]['iLink'])) echo'<a href="'.$I[0]['iLink']."\"></a>"?>             
            </div>
        </div>
<?php if(!empty($I[1])){?>
        <div class="small-12 medium-4 columns">
            <div class="b-img">
<img src="/files/imagenes/i<?php echo $I[1]['iImagen']?>.jpg" alt="" />
<?php if(!empty($I[1]['iLink'])) echo'<a href="'.$I[1]['iLink']."\"></a>"?>             
            </div>
        </div>
<?php }?>
<?php if(!empty($I[2])){?>
        <div class="small-12 medium-4 columns">
            <div class="b-img">
<img src="/files/imagenes/i<?php echo $I[2]['iImagen']?>.jpg" alt="" />
<?php if(!empty($I[2]['iLink'])) echo'<a href="'.$I[2]['iLink']."\"></a>"?>             
            </div>
        </div>
<?php }?>
    </div>
</div>
<?php }?>
</div>

<div id="novedades">
<div class="row">
    <div class="small-12 column">
<?php
        $N= null;
        $rq= $db->query('SELECT * FROM grTienda as t, grTiendaCategorias as c, grTiendaMarcas as m, grTiendaPromociones as p WHERE t.tCategoria=c.cID AND t.tMarca=m.mID AND t.tPromocion=p.pID ORDER BY t.tID DESC LIMIT 10');
        while($rx=$rq->fetchrow()) $N[]=$rx;
        if(!empty($N)) {
            shuffle($N);
?>
    <h2 class="tit-seccion">NOVEDADES</h2>
    <div class="productos-s" style="display:none">
<?php   foreach($N as $D) {?>
        <div class="t-slide" style="margin:2px 10px">
        <div class="t-item">
<div class="t-img">
    <img src="<?php echo'/files/tienda/a'.$D['tImagen'].'.jpg'.$ver?>" alt="<?php echo $D['tNombre']?>" \>
    <?php if(!empty($D['tPromocion'])) echo '<div class="t-promo" style="background-color:'.$D['pColor'].'">'.$D['pNombre'].'</div>'?>
    <a href="/tienda/detalle/<?php echo $D['tID']?>"><?php echo $D['tNombre']?></a>
</div>
<div class="t-blk">
<?php echo '<a class="t-nom" href="/tienda/detalle/'.$D['tID'].'">'.$D['tNombre'].'</a>';
if(!empty($D['tModelo'])) echo '<div class="t-info">Modelo: '.$D['tModelo'].'</div>';
if(!empty($D['tMarca'])) echo '<div class="t-info">Marca: '.$D['mNombre'].'</div>'; ?>
    <a class="t-btn button radius" href="/tienda/detalle/<?php echo $D['tID']?>">DETALLE</a>
</div>
        </div>
        </div>
<?php }?>  
    </div>
    <script type="text/javascript" src="/packages/gr_tienda/js/productos.js?t=<?php echo time()?>"></script>
<?php }?>
    </div>
</div>
</div>
<?php }?>

<?php $a= new Area('Contenido'); $a->display($c)?>

</div>

<?php $this->inc('inc/fin.php')?>