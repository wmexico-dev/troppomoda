<?php  defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd = $this->controller->getTask();
?>

<div class="pago paypal">
<div class="row">
    <div class="small-12 columns end">
    <h2 class="tit-seccion">PAGO CON PAYPAL</h2>
    </div>
</div>
<?php if($xcmd=='directo') {
    if(empty($cliente)) $this->controller->redirect('/registro/');
    $db = Loader::db();
    $R= $db->getRow("SELECT * from grRegistro WHERE rID=".$cliente);
    $D= array();
    if(!empty($R['rInfo'])) $D= json_decode($R['rInfo'],true);
    if(!empty($R['rCompras'])) $D= json_decode($R['rCompras'],true);
?>

<form role="form" id="forma" class="forma" name="forma" method="post" action="/pago/paypal/envio">
<input type="hidden" name="xcmd" value="<?php echo $xcmd?>" />
<input type="hidden" name="xtitulo" value="Pago Directo PayPal" />
<input type="hidden" name="xasunto" value="Pago Troppo <?php echo $orden?>" />
<input type="hidden" name="Orden" value="<?php echo $orden?>" />
<input type="hidden" name="Cliente" value="<?php echo $cliente?>" />

<div class="row">
    <div class="medium-6 columns">
    

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
    <input type="submit" class="button radius fs18" value="Ir a PayPal a pagar el Total">
  </div>
</div>  

    </div>
    <div class="medium-5 medium-offset-1 columns">
        <div class="resumen">
            <p>Pago directo : <?php echo $orden?></p>
            <div class="row">
              <div class="small-12 columns">
                <label><strong>Total a Pagar</strong>
                  <input type="text" class="formaInput" id="pagar" name="TotalPagar" value="" required />
                </label>
              </div>
            </div>
            <input type="hidden" name="xtitulos[TotalPagar]" value="Total a Pagar" />
            <p>&nbsp;<br />Realizar un pago seguro con &nbsp;<span class="lg lg-paypal">PayPal</span></p>
        </div> 
    </div>
</div>

</form>
<script>
$("#forma").validate();
$("#pagar").rules("add",{
    number:true,
    minlength:3
});
</script>
    
<?php } else {?>

<div class="row">
    <div class="small-12 columns end">
<?php if($xcmd=='cancelado') {?>
        <h4>Se ha cancelado su intención de pago con Paypal</h4>
        <p>&nbsp;</p>
        <p><a class="button radius small" href="/tienda/compras/">Ir a Compras</a></p>
<?php } elseif($xcmd=='procesado') {?>
        <h4>Se ha procesado su pago con Paypal</h4>
        <p>&nbsp;</p>
        <p>Se le enviará la confirmación de su compra por correo electrónico</p>
        <p>Nos comunicaremos a la brevedad para los detalles de su orden</p>
<?php }?>
    </div>
</div>

<?php }?>
</div>
