<?php   defined("C5_EXECUTE") or die(_("Access Denied."));

Class AdminTextosInformacionController Extends Controller {

    const aDir = "/_admin/textos/";
    
    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }

    public function on_start() {
        $html = Loader::helper('html');
        $this->set('xerr',null);
        if ($this->getTask()=='agregar' or $this->getTask()=='editar') {
            $this->addHeaderItem('<style type="text/css">form td.formaLabel {white-space:nowrap;line-height:1.6}</style>');
            $this->addHeaderItem($html->css("jquery.ui.css"));
            $this->addHeaderItem($html->javascript('jquery.form.js'));
            $this->addHeaderItem($html->javascript("jquery.ui.js"));
            $this->addHeaderItem($html->css("ccm.app.css"));
            $this->addHeaderItem('<script type="text/javascript" src="'.REL_DIR_FILES_TOOLS_REQUIRED.'/i18n_js"></script>'); 
            $this->addHeaderItem($html->javascript('ccm.app.js'));
            $this->addHeaderItem($html->javascript('tiny_mce/tiny_mce.js'));
            $this->addHeaderItem($html->javascript('i.tinymce.js','gr_textos'));
            $this->addHeaderItem('<script type="text/javascript">$(document).ready(function() { ccm_activateFileSelectors(); });</script>');
        }
    }
    
    public function agregar($id=0) {
        $id = (integer)$id;
        $db = Loader::db();
        $rq = $db->query("SELECT * FROM grTextos WHERE dID=".$id);
        if (!($rx=$rq->fetchrow())) $this->redirect(self::aDir);
        $rx['iID']= 0;
        $this->set('xdat',$rx);
    }
    
    public function editar($id=0) {
        $id = (integer)$id;
        $db = Loader::db();
        $rq = $db->query("SELECT * FROM grTextosInformacion as i, grTextos as d WHERE i.dID=d.dID AND iID=".$id);
        if (!($rx=$rq->fetchrow())) $this->redirect(self::aDir);
        $this->set('xdat',$rx);
        $this->addHeaderItem('<script type="text/javascript">function eliminar() { var resp = confirm("¿Desea eliminar la Información?"); if (resp) { document.forma.xcmd.value = \'eliminar\'; document.forma.submit(); }}</script>', 'CONTROLLER');
    }

    public function actualizar() {
        foreach($_POST as $k => $v) { if(is_string($v)) $_POST[$k]=trim($v); }
        $xerr = null;
        if(empty($_POST['xcmd'])) $this->redirect(self::aDir .'?e=actualizar_no_xcmd');
        $xcmd = $_POST['xcmd'];
        $xdat = $_POST;
        if($xcmd=='agregar') $xdat['iID']=time();
        if(empty($xdat['iID'])) $this->redirect(self::aDir .'?e=actualizar_no_id');
        if(empty($xdat['dID'])) $this->redirect(self::aDir .'?e=actualizar_no_dID');
        $db = Loader::db();
        if($xcmd=='eliminar') {
            $rq = $db->query("DELETE FROM grTextosInformacion WHERE iID=".$xdat['iID']);
            $this->redirect(self::aDir .'informacion/'.$xdat['dID'].'?m=eliminar_ok');
        }
        if(empty($xdat['iTitulo'])) $xerr[]='Falta el Título';
        if(empty($xdat['iTipo']) or !is_numeric($xdat['iTipo'])) $xdat['iTipo']=10;
        if(empty($xdat['iIndice']) or !is_numeric($xdat['iIndice'])) $xdat['iIndice']=0;
        if(empty($xerr)) {
            if($xcmd=='agregar') {
                $sql = "INSERT INTO grTextosInformacion VALUES(".$xdat['iID'].",".$xdat['dID'].",".$xdat['iTipo'].",".$xdat['iIndice'].",".self::stxt($xdat['iTitulo']).",".self::stxt($xdat['iInfo']).");";
            } else {  //editar
                $sql = "UPDATE grTextosInformacion SET iTitulo=".self::stxt($xdat['iTitulo']).",iInfo=".self::stxt($xdat['iInfo'])." WHERE iID=".$xdat['iID'];
            }
            $rq = $db->query($sql);
            $this->redirect(self::aDir .'informacion/'.$xdat['dID'].'?m=actualizar_ok');
        }
        $this->set('xerr',$xerr);
        $this->set('xcmd',$xcmd);
        $this->set('xdat',$xdat);
    }
    
    public function reordenar($id=0) {
        if(empty($id)) $this->redirect(self::aDir);
        if(!empty($_SERVER["HTTP_REFERER"]) and is_array($_GET['n'])) {
            $db= Loader::db(); $n=1;
            foreach ($_GET['n'] as $iID) {
              $rq = $db->query("UPDATE grTextosInformacion SET iIndice=".$n++." WHERE iID=".$iID);
            }
        }
        $this->redirect(self::aDir .'informacion/'.$id.'?m=reordenar_ok');
    }

    public function ordenable($id=0) {
        if(empty($id)) $this->redirect(self::aDir);
        $db = Loader::db();
        $rq = $db->query("SELECT * FROM grTextos WHERE dID=".$id);
        if (!($rx=$rq->fetchrow())) $this->redirect(self::aDir);
        $this->set('xdat',$rx);
        $html = Loader::helper('html');
        $c = Page::getCurrentPage();
        if (!$c->isEditMode()) {
            $this->addHeaderItem($html->css("jquery.ui.css"));
            $this->addHeaderItem($html->javascript("jquery.ui.js"));
            $this->addHeaderItem("<script type=\"text/javascript\"> $(document).ready(function(){ $('#guardarorden').hide(); $('#guardarorden').css('visibility','visible'); $('ul.ordenable').sortable({ cancel: '.noOrd', update: function() { $('#guardarorden').show(); } }); }); function reordenar() { location.href = '". self::aDir ."informacion/reordenar/".$id."?' + $('ul.ordenable').sortable('serialize'); return false; }</script>", 'CONTROLLER');
            $this->addHeaderItem('<style type="text/css">ul.ordenable {cursor:n-resize} .ordenable .noOrd {cursor:auto}</style>','CONTROLLER');
        }
    }

    public function view($id=0) {
        if(empty($id)) $this->redirect(self::aDir);
        $db = Loader::db();
        $rq = $db->query("SELECT * FROM grTextos WHERE dID=".$id);
        if (!($rx=$rq->fetchrow())) $this->redirect(self::aDir);
        $this->set('xdat',$rx);
    }

}
?>