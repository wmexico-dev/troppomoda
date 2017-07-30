<?php   defined("C5_EXECUTE") or die(_("Access Denied."));

Class AdminTiendaImagenesController Extends Controller {

    const aDir = "/_admin/tienda/";

    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }

    public function on_start() {
        $this->set('xerr',null);
    }

    public function view($id=0) {
        $id = (integer)$id;
        $xdat= null;
        $db = Loader::db();
        $sql= "SELECT * FROM grTiendaImagenes where iTipo=1";
        if(!empty($id)) $sql.=" AND tiD=".$id;
        $rq = $db->query($sql);
        while($rx=$rq->fetchrow()) $xdat[]= $rx;
        $this->set('xdat',$xdat);
        $this->addHeaderItem('<style type="text/css">.lista li{font-size:12px;line-height:1.2}</style>');
    }
    
    public function agregar($id=0) {
        $id = (integer)$id;
        $xdat= array('iID'=>0, 'iTienda'=>$id);
        $this->set('xdat',$xdat);
    }
    
    public function editar($id=0) {
        $id = (integer)$id;
        $db = Loader::db();
        $rq = $db->query("SELECT * FROM grTiendaImagenes WHERE iID=".$id);
        if (!($rx=$rq->fetchrow())) $this->redirect(self::aDir);
        $this->set('xdat',$rx);
        $this->addHeaderItem('<script type="text/javascript">function eliminar() { var resp = confirm("¿Desea eliminar la Imagen?"); if (resp) { document.forma.xcmd.value = \'eliminar\'; document.forma.submit(); }}</script>', 'CONTROLLER');
    }

    public function actualizar() {
        foreach($_POST as $k => $v) { if(is_string($v)) $_POST[$k]=trim($v); }
        $fDir= realpath(dirname(__FILE__) .'/../../../../..') . '/files/tienda/';
        $xerr = null;
        if(empty($_POST['xcmd'])) $this->redirect(self::aDir .'?e=actualizar_img_no_xcmd');
        $xcmd = $_POST['xcmd'];
        $xdat = $_POST;
        if($xcmd=='agregar') $xdat['iID']=time();
        if(empty($xdat['iID'])) $this->redirect(self::aDir .'?e=actualizar_img_no_id');
        $db = Loader::db();
        if(empty($xdat['iImagen']) or !is_numeric($xdat['iImagen'])) $xdat['iImagen']=0;
        if($xcmd=='eliminar') {
            $rq = $db->query("DELETE FROM grTiendaImagenes WHERE iID=".$xdat['iID']);
            $f=$fDir.'o'.$xdat['iImagen'].'.jpg'; if(file_exists($f)) unlink($f);
            $f=$fDir.'a'.$xdat['iImagen'].'.jpg'; if(file_exists($f)) unlink($f);
            $row= $db->getRow("SELECT * FROM grTiendaImagenes WHERE iTienda=".$xdat['iTienda'].' ORDER BY iIndice,iNombre,iID');
            if(empty($row)) $ok= $db->execute('UPDATE grTienda SET tImagen=0 WHERE tID='.$xdat['iTienda']);
             else $ok= $db->execute('UPDATE grTienda SET tImagen='.$row['iImagen'].' WHERE tID='.$xdat['iTienda']);
            $this->redirect(self::aDir .'i/'.$xdat['iTienda'] .'?m=eliminar_img_ok');
        }
        if(empty($xdat['iTienda']) or !is_numeric($xdat['iTienda'])) $xdat['iTienda']=0;
        if(empty($xdat['iTipo']) or !is_numeric($xdat['iTipo'])) $xdat['iTipo']=1;
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
            if($xh>$xw) {
                $xw1=(230*$xw)/$xh; $xh1=230;
            } else {
                $xw1=230; $xh1=(230*$xh)/$xw;
            }
            $image1 = imagecreatetruecolor($xw1,$xh1);
            $w = imagecolorallocate($image1, 255, 255, 255); 
            imagefill($image1,0,0,$w);
            if ($ximg==1) $image = imagecreatefromgif($_FILES['archivoImagen']['tmp_name']);
            elseif ($ximg==2) $image = imagecreatefromjpeg($_FILES['archivoImagen']['tmp_name']);
            elseif ($ximg==3) $image = imagecreatefrompng($_FILES['archivoImagen']['tmp_name']);
            else $this->redirect(self::aDir .'?e=actualizar_ximg_create_invalido');
            imagecopyresampled($image1,$image,0,0,0,0,$xw1,$xh1,$xw,$xh);
            $f=$fDir.'o'.$xdat['iImagen'].'.jpg'; if(file_exists($f)) unlink($f);
            $f=$fDir.'a'.$xdat['iImagen'].'.jpg'; if(file_exists($f)) unlink($f);
            $xdat['iImagen']=time();
            imagejpeg($image,$fDir.'o'.$xdat['iImagen'].'.jpg',90);
            imagejpeg($image1,$fDir.'a'.$xdat['iImagen'].'.jpg',90);
        }
        if(empty($xdat['iImagen']) || !file_exists($fDir.'a'.$xdat['iImagen'].'.jpg')) $xerr[]='Falta poner una Imagen';
            else {
            list($xw,$xh,$xt) = getimagesize($fDir.'o'.$xdat['iImagen'].'.jpg');
            $xdat['iAncho']= $xw;
            $xdat['iAltura']= $xh;
        }
        if(empty($xerr)) {
            if(empty($xdat['imagenPrincipal'])) {
                $num= $db->getOne("SELECT COUNT(iID) FROM grTiendaImagenes WHERE iTienda=".$xdat['iTienda']);
                if($num==0) $xdat['iIndice']=1;
            } else {
                $ok= $db->execute("UPDATE grTiendaImagenes SET iIndice=10 WHERE iTienda=".$xdat['iTienda']);
                $xdat['iIndice']=1;
            }
            if($xcmd=='agregar') {
                $ok= $db->execute("INSERT INTO grTiendaImagenes VALUES(".$xdat['iID'].",".$xdat['iTienda'].",".$xdat['iIndice'].",".$xdat['iTipo'].",".$xdat['iImagen'].",".$xdat['iAncho'].",".$xdat['iAltura'].",".self::stxt($xdat['iNombre']).",".self::stxt($xdat['iLink']).",".self::stxt($xdat['iStyle']).",".self::stxt($xdat['iNota']).")");
            } else {
                $ok= $db->execute("UPDATE grTiendaImagenes SET iTienda=".$xdat['iTienda'].",iImagen=".$xdat['iImagen'].",iAncho=".$xdat['iAncho'].",iAltura=".$xdat['iAltura'].",iNombre=".self::stxt($xdat['iNombre']).",iLink=".self::stxt($xdat['iLink']).",iStyle=".self::stxt($xdat['iStyle']).",iNota=".self::stxt($xdat['iNota'])." WHERE iID=".$xdat['iID']);
            }
            if(!empty($xdat['iTienda'])){
                $row= $db->getRow("SELECT * FROM grTiendaImagenes WHERE iTienda=".$xdat['iTienda'].' ORDER BY iIndice,iNombre,iID');
                if(empty($row)) $ok= $db->execute('UPDATE grTienda SET tImagen=0 WHERE tID='.$xdat['iTienda']);
                 else $ok= $db->execute('UPDATE grTienda SET tImagen='.$row['iImagen'].' WHERE tID='.$xdat['iTienda']);
            }
            
            $this->redirect(self::aDir .'i/'.$xdat['iTienda'] .'?m=actualizar_ok');            
        }
        $this->set('xerr',$xerr);
        $this->set('xcmd',$xcmd);
        $this->set('xdat',$xdat);
    }

}
?>