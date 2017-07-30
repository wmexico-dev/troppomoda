<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div id="grTextos<?php echo intval($bID)?>" class="grTextos">
<?php
Loader::model('grTextos','gr_textos');
$db = Loader::db();
$rq = $db->query("SELECT * FROM grTextos WHERE dIdent='".$ident."'");
if($rx=$rq->fetchrow()) {
    if(!empty($rx['dVideo'])) echo '<div class="flex-video" style="margin-bottom:30px"><iframe width="470" height="353" src="'.$rx['dVideo'].'" frameborder="0" allowfullscreen></iframe></div>'."\n";
    echo '<div>'.$rx['dTexto']."</div>\n";
    $I=null;
    $rq = $db->query("SELECT * FROM grTextosInformacion WHERE iTipo=10 AND dID=".$rx['dID'].' ORDER BY iIndice, iTitulo');
    while($X=$rq->fetchrow()) $I[]= $X;
    if(!empty($I)) {
        $a= FALSE;
        echo '<dl class="accordion espacio20" data-accordion>'."\n";
        foreach($I as $A) {
            echo '<dd class="accordion-navigation"><a class="bold" href="#A'.$A['iID'].'"><i class="fa fa-caret-right color"></i> '.grTextos::mayusc($A['iTitulo']).'</a><div id="A'.$A['iID'].'" class="content';
            if(empty($a)) { $a= TRUE; echo ' active'; }
            echo '">'.$A['iInfo'].'</div></dd>';
        }
        echo "</dl>\n";
    }
}?>
</div>