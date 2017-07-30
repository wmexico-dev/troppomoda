<?php  defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd= $this->controller->getTask();
if(!is_array($Compras)) $this->controller->redirect('/tienda/');
$xpago= ($xcmd=='pago')? true : false;
if($xcmd=='orden'  || $xpago) {
    if(empty($cliente)) $this->controller->redirect('/registro/');
    $db = Loader::db();
    $R= $db->getRow("SELECT * from grRegistro WHERE rID=".$cliente);
    $D= array();
    if(!empty($R['rInfo'])) $D= json_decode($R['rInfo'],true);
    if(!empty($R['rCompras'])) $D= json_decode($R['rCompras'],true);
?>

<div class="row">
    <div class="small-12 columns end">
    <h2 class="tit-seccion"><?php if($xpago) echo'PAGAR : '?>ORDEN DE COMPRA</h2>
    </div>
</div>
<div class="row">
    <div class="medium-6 columns">
    
<form role="form" id="forma" class="forma" name="forma" method="post" action="/tienda/compras/enviar">
<input type="hidden" name="xcmd" value="<?php echo $xcmd?>" />
<input type="hidden" name="xtitulo" value="Orden de Compra<?php if($xpago) echo' - PayPal'?>" />
<input type="hidden" name="xasunto" value="Orden Troppo <?php echo $orden?>" />
<input type="hidden" name="Orden" value="<?php echo $orden?>" />
<input type="hidden" name="OrdenTotal" value="<?php echo $Compras['total']?>" />
<input type="hidden" name="Cliente" value="<?php echo $cliente?>" />
<div class="row">
  <div class="small-12 columns">
    <label>Nombre completo
      <input type="text" class="formaInput" name="NombreCompleto" value="<?php echo $D['NombreCompleto']?>" minlength="5" required />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[NombreCompleto]" value="Nombre Completo" />
<div class="row">
  <div class="small-12 columns">
    <label>Correo electrónico
      <input type="email" class="formaInput" name="CorreoElectronico" value="<?php echo $D['CorreoElectronico']?>" required />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[CorreoElectronico]" value="Correo electrónico" />
<div class="row">
  <div class="small-12 columns">
    <label>Empresa
      <input type="text" class="formaInput" name="Empresa" value="<?php echo $D['Empresa']?>" />
    </label>
  </div>
</div>
<div class="row">
  <div class="small-12 columns">
    <label>Teléfono
      <input type="text" class="formaInput" name="Telefono" value="<?php echo $D['Telefono']?>" />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[Telefono]" value="Teléfono" />
<div class="row">
  <div class="small-12 columns">
    <label>Teléfono móvil
      <input type="text" class="formaInput" name="TelefonoMovil" value="<?php echo $D['TelefonoMovil']?>" required />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[TelefonoMovil]" value="Teléfono móvil" />
<div class="row">
  <div class="small-12 columns">
    <label>Comentarios
      <textarea name="Comentarios" rows="3"></textarea>
    </label>
  </div>
</div>
<div class="row">
  <div class="small-12 columns">
    <input type="checkbox" id="factura" name="Factura" value="Sí"> <label for="factura">¿Va a requerir factura?</label>
  </div>
</div>
<div id="facturaCampos" style="display:none">

<div class="row">
  <div class="small-12 columns">
    <label>Registro Federal de Contribuyentes (RFC)
      <input type="text" class="formaInput" name="RFC" value="<?php echo $D['RFC']?>" required />
    </label>
  </div>
</div>
<div class="row">
  <div class="small-12 columns">
    <label>Nombre Fiscal
      <input type="text" class="formaInput" name="NombreFac" value="<?php echo $D['NombreFac']?>" minlength="5" required />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[NombreFac]" value="Nombre Factura" />
<div class="row">
  <div class="small-12 columns">
    <label>Correo electrónico para facturas
      <input type="email" class="formaInput" name="CorreoFac" value="<?php echo $D['CorreoFac']?>" required />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[CorreoFac]" value="Correo p/facturas" />
<div class="row">
  <div class="small-12 columns">
    <label>Dirección Fiscal (calle,número,interior)
      <input type="text" class="formaInput" name="DireccionFac" value="<?php echo $D['DireccionFac']?>" minlength="5" required />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[DireccionFac]" value="Dirección" />
<div class="row">
  <div class="small-12 columns">
    <label>Colonia
      <input type="text" class="formaInput" name="ColoniaFac" value="<?php echo $D['ColoniaFac']?>" />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[ColoniaFac]" value="Colonia" />
<div class="row">
  <div class="small-12 columns">
    <label>Ciudad
      <input type="text" class="formaInput" name="CiudadFac" value="<?php echo $D['CiudadFac']?>" minlength="4" required />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[CiudadFac]" value="Ciudad" />
<div class="row">
  <div class="small-12 columns">
    <label>Municipio
      <input type="text" class="formaInput" name="MunicipioFac" value="<?php echo $D['MunicipioFac']?>" />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[MunicipioFac]" value="Municipio" />
<div class="row">
  <div class="small-12 columns">
    <label>Estado
      <input type="text" class="formaInput" name="EstadoFac" value="<?php echo $D['EstadoFac']?>" minlength="3" required />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[EstadoFac]" value="Estado" />
<div class="row">
  <div class="small-12 columns">
    <label>Código Postal
      <input type="text" class="formaInput" name="CodigoPostalFac" value="<?php echo $D['CodigoPostalFac']?>" minlength="5" required />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[CodigoPostalFac]" value="Código Postal" />

</div>
<div class="row" style="padding-top:10px">
  <div class="small-12 columns">
    <input type="submit" class="button radius fs18" value="<?php echo ($xpago)?'Ir a PayPal a pagar':'Enviar'?> Orden de Compra">
  </div>
</div>  
</form>
<script>
$("#forma").validate();
</script>

    </div>
    <div class="medium-5 medium-offset-1 columns">
        <div class="resumen">
            <p>Orden de Compra : <?php echo $orden?><br />
            TOTAL a pagar : &nbsp;<span class="fs18 bold">$ <?php echo number_format($Compras['total'],2) ?></span></p>
<?php if($xpago){?>
            <p>Pago seguro con &nbsp;<span class="lg lg-paypal">PayPal</span></p>
<?php }else{?>
            <p class="listaDeposito">Deposite su pago para <strong>Troppo Moda, SA de CV</strong> en:<br class="e"/>
SANTANDER &nbsp;65-50519161-4<br />
CLABE 014180655051916145<br class="e"/>
BANCOMER &nbsp;0101519859<br />
CLABE 012180001015198597<br class="e"/>
SCOTIABANK &nbsp;00107839285<br />
CLABE 044180001078392858<br class="e"/>
OXXO &nbsp;5579-2090-8699-7217</p>
<?php }?>
            </p>
            Compras :
            <ul class="listaCompras">
<?php foreach($Compras['compras'] as $X) {
    echo '<li><strong>'.$X['cantidad'].'</strong>';
    if(!empty($X['unidad'])) echo ' '.$X['unidad'];
    echo ' : '.$X['nombre'].'<br />';
    $x='';
    if(!empty($X['modelo'])) $x.='mod. '.$X['modelo'];
    if(!empty($X['marca'])) $x.=' '.$X['marca'];
    if(!empty($x)) echo trim($x).'<br />';
    echo '$'.number_format($X['subtotal'],2);
    if(!empty($X['promocion'])) echo ' '.$X['promocion'];
    echo "</li>\n";
}?>
            </ul>
        </div> 
    </div>
</div>

<?php } else {?>

<div id="compras">
<div class="row">
    <div class="small-12 columns">    

    <h2 class="tit-seccion">LISTA DE COMPRAS</h2>
    <table style="width:100%">
        <thead>
            <td width="10px" class="c-cant">CANTIDAD</td>
            <td width="10px"></td>
            <td width="10px"></td>
            <td class="c-nombre"><div>ARTÍCULO</div></td>
            <td width="40px"></td>
            <td width="10px" class="c-precio"><div>PRECIO</div></td>
            <td width="10px" class="c-subtotal"><div>SUBTOTAL</div></td>
            <td width="10px"></td>
        </thead>
<?php foreach($Compras['compras'] as $L) {?>
        <tr>
            <td class="c-cant"><?php echo $L['cantidad']?></td>
            <td class="c-mas"><a href="/tienda/compras/o/comprar/<?php  echo $L['id']?>/1"><i class="fa fa-plus-square-o"></i></a></td>
            <td class="c-menos"><a href="/tienda/compras/o/comprar/<?php  echo $L['id']?>/-1"><i class="fa fa-minus-square-o"></i></a></td>
            <td class="c-nombre"><div><?php if(!empty($L['unidad'])) echo $L['unidad'].' - '; echo $L['nombre']; ?></div></td>
            <td class="c-img"><img src="/files/tienda/a<?php if(!empty($L['imagen'])) echo $L['imagen']?>.jpg" alt="" style="max-width:40px" /></td>
            <td class="c-precio"><div><?php  echo  number_format($L['precio'],2)?></div></td>
            <td class="c-subtotal"><div><?php  echo  number_format($L['subtotal'],2)?></div></td>
            <td class="c-elim"><a href="/tienda/compras/o/eliminar/<?php  echo $L['id']?>"><i class="fa fa-times"></td>
        </tr>
<?php }?>
    </table>
    <div class="c-total"><span style="color:#555">TOTAL:</span> &nbsp; $ <?php  echo number_format($Compras['total'],2) ?></div>

    <div class="row" style="margin-top:32px">
        <div class="small-5 small-offset-1 columns centro">
            <a class="button radius" href="/tienda/compras/orden/">Enviar una Orden de Compra</a>
        </div>
        <div class="small-5 columns end centro">
            <a class="button radius" href="/tienda/compras/pago/">Pagar en línea la Compra</a>
        </div>
    </div>
    
    </div>
</div>
</div>
<?php }?>