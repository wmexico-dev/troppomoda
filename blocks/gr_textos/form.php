<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>  

<h2>Identificación</h2>

<?php
    $Dat= null;
    $db= Loader::db();
    $rq= $db->query("SELECT * FROM grTextos ORDER BY dTitulo");
    while($rx=$rq->fetchrow()) $Dat[]=$rx;
    if(!empty($Dat)) {
?>
   <div class="row">
        <div class="small-12 columns">
        <label>
            <select name="ident" class="formaSelect">
                <option value="">--</option>
<?php foreach($Dat as $opx) { echo '<option value="'.$opx['dIdent'].'"'; if($opx['dIdent']==$ident) echo ' selected'; echo '>'.$opx['dTitulo']."</option>\n"; } ?>
            </select>
        </label>
        </div>
    </div>
<?php  }?>
