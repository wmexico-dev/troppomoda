<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div id="grImagenes<?php echo intval($bID)?>" class="grImagenes <?php echo $ident?>">
<?php
    Loader::model('grImagenes','gr_imagenes');
    $I= grImagenes::imagenes($ident);
    if(!empty($I)) {
        shuffle($I);
        echo '<img src="/files/imagenes/i'.$I[0]['iImagen'].'.jpg" class="res" />';
//        echo'<div style="height:'.$I[0]['iAltura'].'px;background:url(\'/files/imagenes/i'.$I[0]['iImagen'].'.jpg\') no-repeat center 0;">'."</div>\n";
    }
?>
</div>