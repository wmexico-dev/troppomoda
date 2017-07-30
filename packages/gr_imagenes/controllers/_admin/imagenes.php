<?php   defined("C5_EXECUTE") or die(_("Access Denied."));

Class AdminImagenesController Extends Controller {

    const aDir = "/_admin/imagenes/";
    
    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }

    public function on_start() {
        $html = Loader::helper('html');
        $this->set('xerr',null);
    }
    
    public function view() {
        $this->addHeaderItem('<style type="text/css">.lista li{font-size:13px}</style>');
    }
    
    public function agregar() {
        $xdat = array('iID'=>0);
        $this->set('xdat',$xdat);
    }
    
    public function editar($id=0) {
        $id = (integer)$id;
        $db = Loader::db();
        $rq = $db->query("SELECT * FROM grImagenes WHERE iID=".$id);
        if (!($rx=$rq->fetchrow())) $this->redirect(self::aDir);
        $this->set('xdat',$rx);
        $this->addHeaderItem('<script type="text/javascript">function eliminar() { var resp = confirm("¿Desea eliminar la Imagen?"); if (resp) { document.forma.xcmd.value = \'eliminar\'; document.forma.submit(); }}</script>', 'CONTROLLER');
    }
    
    public function actualizar() {
        foreach($_POST as $k => $v) { if(is_string($v)) $_POST[$k]=trim($v); }
        $fDir= realpath(dirname(__FILE__) .'/../../../..') . '/files/imagenes/';
        $xerr = null;
        if(empty($_POST['xcmd'])) $this->redirect(self::aDir .'?e=actualizar_no_xcmd');
        $xcmd = $_POST['xcmd'];
        $xdat = $_POST;
        if($xcmd=='agregar') $xdat['iID']=time();
        if(empty($xdat['iID'])) $this->redirect(self::aDir .'?e=actualizar_no_id');
        $db = Loader::db();
        if($xcmd=='eliminar') {
            $rq = $db->query("DELETE FROM grImagenes WHERE iID=".$xdat['iID']);
            if(!empty($xdat['iImagen'])) unlink($fDir.'i'.$xdat['iImagen'].'.jpg');
            $this->redirect(self::aDir .'?m=eliminar_ok');
        }
        if(empty($xdat['cID']) or !is_numeric($xdat['cID'])) $xdat['cID']=0;
        if(empty($xdat['iTipo']) or !is_numeric($xdat['iTipo'])) $xdat['iTipo']=1;
        if(empty($xdat['iImagen']) or !is_numeric($xdat['iImagen'])) $xdat['iImagen']=0;
        if(empty($xdat['iAncho']) or !is_numeric($xdat['iAncho'])) $xdat['iAncho']=0;
        if(empty($xdat['iAltura']) or !is_numeric($xdat['iAltura'])) $xdat['iAltura']=0;
        if(empty($xdat['iNota'])) $xdat['iNota']='';
        if(empty($xdat['iIndice']) or !is_numeric($xdat['iIndice'])) $xdat['iIndice']=10;
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
        if($ximg>0) {
            list($xw,$xh) = getimagesize($_FILES['archivoImagen']['tmp_name']);
            if ($ximg==1) $image = imagecreatefromgif($_FILES['archivoImagen']['tmp_name']);
            elseif ($ximg==2) $image = imagecreatefromjpeg($_FILES['archivoImagen']['tmp_name']);
            elseif ($ximg==3) $image = imagecreatefrompng($_FILES['archivoImagen']['tmp_name']);
            else $this->redirect(self::aDir .'?e=actualizar_ximg_invalido');
            if(!empty($xdat['iImagen'])) unlink($fDir.'i'.$xdat['iImagen'].'.jpg');
            $xdat['iImagen']= time();
            imagejpeg($image,$fDir.'i'.$xdat['iImagen'].'.jpg',90);
        }
        if(empty($xdat['iImagen']) || !file_exists($fDir.'i'.$xdat['iImagen'].'.jpg')) $xerr[]='Falta poner una Imagen';
         else {
            list($xw,$xh,$xt) = getimagesize($fDir.'i'.$xdat['iImagen'].'.jpg');
            $xdat['iAncho']= $xw;
            $xdat['iAltura']= $xh;
        }
        if(empty($xerr)) {
            if($xcmd=='agregar') {
                $rq = $db->query("INSERT INTO grImagenes VALUES(".$xdat['iID'].",".$xdat['cID'].",".$xdat['iIndice'].",".$xdat['iTipo'].",".self::stxt($xdat['iNombre']).",".$xdat['iImagen'].",".$xdat['iAncho'].",".$xdat['iAltura'].",".self::stxt($xdat['iLink']).",".self::stxt($xdat['iStyle']).",".self::stxt($xdat['iNota']).")");
            } else {
                $rq = $db->query("UPDATE grImagenes SET cID=".$xdat['cID'].",iNombre=".self::stxt($xdat['iNombre']).",iImagen=".$xdat['iImagen'].",iAncho=".$xdat['iAncho'].",iAltura=".$xdat['iAltura'].",iLink=".self::stxt($xdat['iLink']).",iStyle=".self::stxt($xdat['iStyle']).",iNota=".self::stxt($xdat['iNota'])." WHERE iID=".$xdat['iID']);
            }
            $this->redirect(self::aDir .'?m=actualizar_ok');            
        }
        $this->set('xerr',$xerr);
        $this->set('xcmd',$xcmd);
        $this->set('xdat',$xdat);
    }

}
?>