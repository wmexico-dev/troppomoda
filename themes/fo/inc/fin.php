<?php  defined('C5_EXECUTE') or die("Access Denied.");
global $c;
$xch= $c->getCollectionHandle();
$xcn= $c->getCollectionID();
$xtp= $this->getThemePath();
?>

<div id="fin">
<div class="row">
    <div class="small-12 medium-7 medium-offset-1 columns espacio">
<?php  $a = new GlobalArea('Fin-a'); $a->display($c);?>            
    </div>
    <div class="small-12 medium-4 columns espacio">
    <div class="derecho">
<?php  $a = new GlobalArea('Fin-r'); $a->display($c);?>           
    </div>
    </div>
</div>
<div class="row">
    <div class="small-12 columns aviso">
&copy; <?php echo date('Y')?> Troppo di Moda. &nbsp;Derechos Reservados - All Rights Reserved. <br />
Insurgentes Sur 1216 Int 504, Col Tlacoquemécatl del Valle, Benito Juárez, CP 03200, Ciudad de México. <br />
El contenido e imágenes utilizados en este sitio son propiedad o están bajo licencia de sus propietarios. <br />
Los precios publicados en esta tienda están sujetos a cambio sin previo aviso y sólo son aplicables para venta en línea.        
    </div>
</div>
</div>

<?php  Loader::element('footer_required'); ?>

<?php if (!$c->isEditMode()) {?>
<script src="<?php echo $xtp?>/js/vendor/fastclick.js"></script>
<script src="<?php echo $xtp?>/js/foundation.min.js"></script>
<script>
  $(document).foundation();
</script>
<?php }?>

</body>
</html>