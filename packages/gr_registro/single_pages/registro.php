<?php defined('C5_EXECUTE') or die(_("Access Denied."));
if(empty($xcmd)) $xcmd= $this->controller->getTask();
$db= Loader::db();
?>
<div id="registro">
<?php if($xcmd=='olvido') { ?>

    <div class="row">
        <div class="small-12 columns">
            <h2 class="tit-seccion">REGISTRO</h2>
            <p style="padding-bottom:30px">Si olvidó la Contraseña, puede asignar una nueva.<br />Se enviará un correo para confirmar.</p>
            <?php if(is_array($xerr)) { echo '<div class="error"><ul>'; foreach ($xerr as $e) echo'<li>'.$e.'</li>'; echo "</ul></div>\n"; }?>
            <form role="form" class="forma" id="olvido" name="entrar" method="post" action="/registro/olvido/">
            <input name="xcmd" type="hidden" value="<?php echo $xcmd?>" />
            <div class="row">
                <div class="medium-6 small-12 columns">
                <label>E-mail/Correo registrado:
                    <input class="formaInput" name="rEmail" type="email" value="<?php echo $xdat['rEmail']?>" required />
                </label>
                </div>
            </div>
            <div class="row">
                <div class="medium-6 small-12 columns">
                <label>Nueva contraseña:
                    <input class="formaInput" name="rClave" type="password" value="" required />
                </label>
                </div>
            </div>
            <div class="row">
                <div class="medium-6 small-12 columns">
                <label>Repetir nueva contraseña:
                    <input class="formaInput" name="rClave1" type="password" value="" required />
                </label>
                </div>
            </div>
            <div class="row" style="padding-top:12px">
                <div class="medium-6 small-12 columns">
                <label>
                    <input class="formaSubmit button radius" type="submit" value="Enviar" />
                </label>
                </div>
            </div>
            </form>
            <script>
            $("#olvido").validate();
            </script>
            <p class="espacio"></p>
        </div>
    </div>

<?php } elseif($xcmd=='olvidoEnviado') { ?>
    
    <div class="row">
        <div class="small-12 columns">
            <h2 class="tit-seccion">REGISTRO</h2>
            <p class="fs18 bold verde" style="padding-top:10px">Se ha enviado un enlace de confirmación a su correo.</p>
            <p>La confirmación será válida por dos horas.</p>
            <p class="espacio"></p>
        </div>
    </div>

<?php } elseif($xcmd=='olvidoConfirmado') { ?>
    
    <div class="row">
        <div class="small-12 columns">
            <h2 class="tit-seccion">REGISTRO</h2>
            <p style="padding-bottom:30px">Ya puede registrarse con su nueva contraseña.</p>
            <form role="form" class="forma" id="entrar" name="entrar" method="post" action="/registro/entrar/">
            <input name="xcmd" type="hidden" value="<?php echo $xcmd?>" />
            <div class="row">
                <div class="medium-6 small-12 columns">
                <label>E-mail/Correo:
                    <input class="formaInput" name="rEmail" type="email" value="" required />
                </label>
                </div>
            </div>
            <div class="row">
                <div class="medium-6 small-12 columns">
                <label>Contraseña:
                    <input class="formaInput" name="rClave" type="password" value="" required />
                </label>
                </div>
            </div>
            <div class="row" style="padding-top:12px">
                <div class="medium-6 small-12 columns">
                <label>
                    <input class="formaSubmit button radius" type="submit" value="Entrar" />
                </label>
                </div>
            </div>
            </form>
            <script>
            $("#entrar").validate();
            </script>
            <p class="espacio"></p>
        </div>
    </div>

<?php } else { ?>

    <div class="row">
        <div class="small-12 columns">
            <h2 class="tit-seccion">REGISTRO</h2>
            <p style="padding-bottom:30px">Para poder ver los precios y realizar una orden de compra es necesario que se registre.</p>
        </div>
        <div class="small-12 medium-4 columns">
            <p class="fs18 bold">Si ya está registrado:</p>
        <?php if($xcmd=='incorrecto') { ?>
            <p class="error"> El correo o la contraseña son incorrectos.</p>
        <?php } ?>
            <form role="form" class="forma" id="entrar" name="entrar" method="post" action="/registro/entrar/">
            <input name="xcmd" type="hidden" value="<?php echo $xcmd?>" />
            <div class="row">
                <div class="small-12 columns">
                <label>E-mail/Correo:
                    <input class="formaInput" name="rEmail" type="email" value="" required />
                </label>
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                <label>Contraseña:
                    <input class="formaInput" name="rClave" type="password" value="" required />
                </label>
                </div>
            </div>
            <p><a href="/registro/olvido">¿Olvidó su contraseña?</a></p>
            <div class="row" style="padding-top:12px">
                <div class="small-12 columns">
                <label>
                    <input class="formaSubmit button radius" type="submit" value="Entrar" />
                </label>
                </div>
            </div>
            </form>
            <script>
            $("#entrar").validate();
            </script>
            <p class="espacio"></p>
        </div>
        <div class="small-12 medium-6 medium-offset-2 columns">
            <p class="fs16"><strong>Si no se ha registrado</strong>, favor de llenar sus datos para recibir por correo electrónico su clave de acceso.</p>
<form role="form" class="forma" id="enviar" name="enviar" method="post" action="/registro/enviar/">
<input type="hidden" name="xcmd" value="registro" />
<input type="hidden" name="xtitulo" value="Registro TROPPO" />
<input type="hidden" name="xasunto" value="Registro TROPPO" />
<div class="row">
  <div class="small-12 columns">
    <label>Nombre completo
      <input type="text" class="formaInput" name="NombreCompleto" value="" minlength="5" required />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[NombreCompleto]" value="Nombre Completo" />
<div class="row">
  <div class="small-12 columns">
    <label>Correo electrónico
      <input type="email" class="formaInput" name="CorreoElectronico" value="" required />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[CorreoElectronico]" value="Correo electrónico" />
<div class="row">
  <div class="small-12 columns">
    <label>Empresa
      <input type="text" class="formaInput" name="Empresa" value="" />
    </label>
  </div>
</div>
<div class="row">
  <div class="small-12 columns">
    <label>Teléfono
      <input type="text" class="formaInput" name="Telefono" value="" />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[Telefono]" value="Teléfono" />
<div class="row">
  <div class="small-12 columns">
    <label>Teléfono móvil
      <input type="text" class="formaInput" name="TelefonoMovil" value="" required />
    </label>
  </div>
</div>
<input type="hidden" name="xtitulos[TelefonoMovil]" value="Teléfono móvil" />
<div class="row">
  <div class="small-12 columns">
    <label>Comentarios
      <textarea name="Comentarios" rows="7"></textarea>
    </label>
  </div>
</div>
<div class="row" style="padding-top:10px">
  <div class="small-12 columns">
    <input type="submit" class="button radius" value="Enviar">
  </div>
</div>  
</form>
<script>
$("#enviar").validate();
</script>
            <p class="espacio"></p>
        </div>
    </div>
    
<?php } ?>
</div>
