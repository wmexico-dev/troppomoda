<?php   defined("C5_EXECUTE") or die(_("Access Denied."));

Class AdminTextosController Extends Controller {

    const aDir = "/_admin/textos/";

    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }

    public function on_start() {
        $html = Loader::helper('html');
        $this->set('xerr',null);
        if ($this->getTask()=='agregar' or $this->getTask()=='editar') {
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

    public function view() {
        $this->addHeaderItem('<style type="text/css">.lista .f-dropdown li{font-size:12px}</style>');
    }
    
    public function agregar() {
        $xdat = array('dID'=>0);
        $this->set('xdat',$xdat);
    }
    
    public function editar($id=0) {
        $id = (integer)$id;
        $db = Loader::db();
        $rq = $db->query("SELECT * FROM grTextos WHERE dID=".$id);
        if (!($rx=$rq->fetchrow())) $this->redirect(self::aDir);
        $this->set('xdat',$rx);
        $this->addHeaderItem('<script type="text/javascript">function eliminar() { var resp = confirm("¿Desea eliminar el Texto?"); if (resp) { document.forma.xcmd.value = \'eliminar\'; document.forma.submit(); }}</script>', 'CONTROLLER');
    }

    public function actualizar() {
        foreach($_POST as $k => $v) { if(is_string($v)) $_POST[$k]=trim($v); }
        $fDir= realpath(dirname(__FILE__) .'/../../../..') . '/files/textos/';
        $xerr = null;
        if(empty($_POST['xcmd'])) $this->redirect(self::aDir .'?e=actualizar_no_xcmd');
        $xcmd = $_POST['xcmd'];
        $xdat = $_POST;
        if($xcmd=='agregar') $xdat['dID']=time();
        if(empty($xdat['dID'])) $this->redirect(self::aDir .'?e=actualizar_no_id');
        $db = Loader::db();
        if($xcmd=='eliminar') {
            $rq = $db->query("DELETE FROM grTextos WHERE dID=".$xdat['dID']);
            $rq = $db->query("DELETE FROM grTextosInformacion WHERE dID=".$xdat['dID']);
            if(!empty($xdat['dImagen'])) unlink($fDir.'i'.$xdat['dImagen'].'.jpg');
            $this->redirect(self::aDir .'?m=eliminar_ok');
        }
        if(!empty($xdat['eliminarImagen'])) {
            if(!empty($xdat['dImagen'])) unlink($fDir.'i'.$xdat['dImagen'].'.jpg');
            $xdat['dImagen']=0;
            unset($_FILES['archivoImagen']);
        }
        if(empty($xdat['dTitulo'])) $xerr[]='Falta el Título del Texto';
        if(empty($xdat['dIdent'])) {
            Loader::model('grTextos','gr_textos');
            $xdat['dIdent']= grTextos::ident($xdat['dTitulo']);
        }
        $rq = $db->query("SELECT dID FROM grTextos WHERE dIdent=".self::stxt($xdat['dIdent']));
        if($rx=$rq->fetchrow()) { if($rx['dID']!=$xdat['dID']) $xerr[]='Ya existe esa Identificación'; }
        $regExp= '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/';
        if(!empty($xdat['dVideo'])) if(preg_match($regExp, $xdat['dVideo'], $matches)) {
            if(strlen($matches[2])==11) $xdat['dVideo']='https://www.youtube.com/embed/'.$matches[2];
            else $xerr[]='El URL del video no es un URL de YouTube válido';
        } else $xerr[]='El URL del video no es un URL de YouTube válido';
        if(empty($xdat['dTipo']) or !is_numeric($xdat['dTipo'])) $xdat['dTipo']=1;
        if(empty($xdat['dImagen']) or !is_numeric($xdat['dImagen'])) $xdat['dImagen']=0;
        $ximg=0;
        if(!empty($_FILES['archivoImagen']['name'])) {
            if (is_uploaded_file($_FILES['archivoImagen']['tmp_name'])) {
                if (empty($_FILES['archivoImagen']['error'])) {
                    if ($_FILES['archivoImagen']['type']=='image/gif') $ximg=1;
                    elseif ($_FILES['archivoImagen']['type']=='image/jpeg') $ximg=2;
                    elseif ($_FILES['archivoImagen']['type']=='image/pjpeg') $ximg=2;
                    elseif ($_FILES['archivoImagen']['type']=='image/png') $ximg=3;
                    elseif ($_FILES['archivoImagen']['type']=='image/x-png') $ximg=3;
                    else $xerr[]='Sólo imágenes tipo JPEG, GIF o PNG : '.$_FILES['archivoImagen']['type'];    
                } else $xerr[]='Error de captura de Imagen : '.$_FILES['archivoImagen']['error'];
            } else $xerr[]='Falló la captura de la Imagen';
        }
        if(empty($xerr)) {
            if($ximg>0) {
                list($xw,$xh) = getimagesize($_FILES['archivoImagen']['tmp_name']);
                if ($ximg==1) $image = imagecreatefromgif($_FILES['archivoImagen']['tmp_name']);
                elseif ($ximg==2) $image = imagecreatefromjpeg($_FILES['archivoImagen']['tmp_name']);
                elseif ($ximg==3) $image = imagecreatefrompng($_FILES['archivoImagen']['tmp_name']);
                else $this->redirect(self::aDir .'?e=actualizar_ximg_invalido');
                if(!empty($xdat['dImagen'])) unlink($fDir.'i'.$xdat['dImagen'].'.jpg');
                $xdat['dImagen']= time();
                imagejpeg($image,$fDir.'i'.$xdat['dImagen'].'.jpg',90);
            }                    
            if($xcmd=='agregar') {
                $rq = $db->query("INSERT INTO grTextos VALUES(".$xdat['dID'].",".$xdat['dTipo'].",0,".self::stxt($xdat['dIdent']).",".self::stxt($xdat['dTitulo']).",".$xdat['dImagen'].",".self::stxt($xdat['dTexto']).",".self::stxt($xdat['dVideo']).")");
            } else {
                $rq = $db->query("UPDATE grTextos SET dTipo=".$xdat['dTipo'].",dIdent=".self::stxt($xdat['dIdent']).",dTitulo=".self::stxt($xdat['dTitulo']).",dImagen=".$xdat['dImagen'].",dTexto=".self::stxt($xdat['dTexto']).",dVideo=".self::stxt($xdat['dVideo'])." WHERE dID=".$xdat['dID']);
            }
            $this->redirect(self::aDir .'?m=actualizar_ok');            
        }
        $this->set('xerr',$xerr);
        $this->set('xcmd',$xcmd);
        $this->set('xdat',$xdat);
    }

}
?>