<?php   defined("C5_EXECUTE") or die(_("Access Denied."));

Class AdminTiendaController Extends Controller {

    const aDir= "/_admin/tienda/";

    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }
    private function limpiar($s) { return trim(preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $s)); }

    public function on_start() {
        $html= Loader::helper('html');
        $this->set('xerr',null);
        $this->set('xcmd',null);
        $this->set('xdat',null);
        if($this->getTask()=='agregar' or $this->getTask()=='editar' or $this->getTask()=='actualizar') {
            $this->addHeaderItem($html->css("jquery.ui.css"));
            $this->addHeaderItem($html->javascript('jquery.form.js'));
            $this->addHeaderItem($html->javascript("jquery.ui.js"));
            $this->addHeaderItem($html->css("ccm.app.css"));
            $this->addHeaderItem('<script type="text/javascript" src="'.REL_DIR_FILES_TOOLS_REQUIRED.'/i18n_js"></script>'); 
            $this->addHeaderItem($html->javascript('ccm.app.js'));
            $this->addHeaderItem($html->css('i.tinymce.css','gr_tienda'));
            $this->addHeaderItem($html->javascript('tiny_mce/tiny_mce.js'));
            $this->addHeaderItem($html->javascript('i.tinymce.js','gr_tienda'));
            $this->addHeaderItem('<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>');
            $this->addHeaderItem($html->css('adminTienda.css','gr_tienda'));
            $this->addHeaderItem($html->javascript('adminTienda.js','gr_tienda'));
            $this->addHeaderItem('<script type="text/javascript">$(document).ready(function() { ccm_activateFileSelectors(); });</script>');
        }
    }

    public function view($op=null,$id=null) {
        $this->addHeaderItem('<style type="text/css">.lista .f-dropdown li{font-size:12px}</style>');
        if(!empty($op)) if($op=='i') if(!empty($id)) $this->set('ximg',$id);
    }
    
    public function agregar() {
        $xdat= array('tID'=>0);
        $this->set('xdat',$xdat);
    }
    
    public function editar($id=0) {
        $id= intval($id);
        $db= Loader::db();
        $rq= $db->query("SELECT * FROM grTienda WHERE tID=".$id);
        if (!($rx=$rq->fetchrow())) $this->redirect(self::aDir);
        $this->set('xdat',$rx);
        $this->addHeaderItem('<script type="text/javascript">function eliminar() { var resp= confirm("¿Desea eliminar el Artículo?"); if (resp) { document.forma.xcmd.value= \'eliminar\'; document.forma.submit(); }}</script>', 'CONTROLLER');
    }

    public function actualizar() {
        foreach($_POST as $k=> $v) { if(is_string($v)) $_POST[$k]=trim($v); }
        $fDir= realpath(dirname(__FILE__) .'/../../../..') . '/files/tienda/';
        $xerr= null;
        if(empty($_POST['xcmd'])) $this->redirect(self::aDir .'?e=actualizar_no_xcmd');
        $xcmd= $_POST['xcmd'];
        $xdat= $_POST;
        if($xcmd=='agregar') $xdat['tID']=time();
        if(empty($xdat['tID'])) $this->redirect(self::aDir .'?e=actualizar_no_id');
        $db= Loader::db();
        if(empty($xdat['tImagen']) or !is_numeric($xdat['tImagen'])) $xdat['tImagen']=0;
        if($xcmd=='eliminar') {
            $rq= $db->query("DELETE FROM grTienda WHERE tID=".$xdat['tID']);
            if(is_array($xdat['tImagenes'])) foreach($xdat['tImagenes'] as $I) if(!empty($I['imagen'])) {
                $f=$fDir.'o'.$I['imagen'].'.jpg'; if(file_exists($f)) unlink($f);
                $f=$fDir.'a'.$I['imagen'].'.jpg'; if(file_exists($f)) unlink($f);
            }
            $this->redirect(self::aDir .'?m=eliminar_ok');
        }
        Loader::model('grTienda','gr_tienda');
        if(empty($xdat['tNombre']) && empty($xdat['tModelo'])) $xerr[]='Falta el nombre y/o modelo del Artículo';
        else {
            $xdat['tIdent']= grTienda::ident($xdat['tNombre']);
            if(!empty($xdat['tModelo'])) {
                if(!empty($xdat['tIdent'])) $xdat['tIdent'].='-';
                $xdat['tIdent'].= 'modelo-'.grTienda::ident($xdat['tModelo']);
            }
            if(is_numeric($xdat['tIdent'])) $xdat['tIdent']= 'num-'.$xdat['tIdent'];
            $id= $db->getOne("SELECT tID FROM grTienda WHERE tIdent=".self::stxt($xdat['tIdent']));
            if(!empty($id)) if($id!=$xdat['tID']) $xerr[]='Ya existe esa Identificación (nombre/modelo)';
        }
        if(empty($xdat['tTipo']) or !is_numeric($xdat['tTipo'])) $xdat['tTipo']=0;
        if(empty($xdat['tCategoria']) or !is_numeric($xdat['tCategoria'])) $xdat['tCategoria']=0;
        if(empty($xdat['tMarca']) or !is_numeric($xdat['tMarca'])) $xdat['tMarca']=0;
        if(empty($xdat['tPromocion']) or !is_numeric($xdat['tPromocion'])) $xdat['tPromocion']=0;
        if(empty($xdat['tPrecio']) or !is_numeric($xdat['tPrecio'])) $xdat['tPrecio']=0.0;
        $Precios= null;
        if(!empty($xdat['Precios']) and is_array($xdat['Precios'])) foreach($xdat['Precios'] as $k=>$P) {
            if(is_array($P) and !empty($P['precio'])) {
                $precio= floatval($P['precio']);
                if($precio>0.0) $Precios[ time() + $k ]= array(
                    'precio'=> $precio,
                    'promo'=> floatval($P['promo']),
                    'info'=> $P['info']
                );
            }
        }
        if(!empty($Precios) and is_array($Precios)) $xdat['tPrecios']= json_encode($Precios); 
         else $xdat['tPrecios']=null;
        if(empty($xdat['tVipStatus'])) $xdat['tVipStatus']=0;
        if(empty($xdat['tVipStatus']) OR empty($xdat['tVipDescuento'])) $xdat['tVipDescuento']=0.0;
        $xdat['tVipDescuento']= floatval($xdat['tVipDescuento']);
        if($xdat['tVipDescuento']<0 OR $xdat['tVipDescuento']>100) $xerr[]= 'El Porcentaje de descuento debe ser entre 0 y 100';
        $xdat['tImagen']=0;
        $Imagenes= null; $siPrincipal= false;
        if(is_array($xdat['Imagenes'])) foreach($xdat['Imagenes'] as $k=>$I) {
            if(empty($I['eliminar'])) {
                $imagen= 0; $ximg =0;
                if(!empty($I['imagen'])) $imagen= $I['imagen'];
                if(!empty($_FILES['ImagenArchivo']['name'][$k])) {
                    if (is_uploaded_file($_FILES['ImagenArchivo']['tmp_name'][$k])) {
                        if (empty($_FILES['ImagenArchivo']['error'][$k])) {
                            if ($_FILES['ImagenArchivo']['type'][$k]=='image/gif') $ximg=1;
                            elseif ($_FILES['ImagenArchivo']['type'][$k]=='image/jpeg') $ximg=2;
                            elseif ($_FILES['ImagenArchivo']['type'][$k]=='image/pjpeg') $ximg=2;
                            elseif ($_FILES['ImagenArchivo']['type'][$k]=='image/png') $ximg=3;
                            elseif ($_FILES['ImagenArchivo']['type'][$k]=='image/x-png') $ximg=3;
                        }
                    }
                }
                if($ximg>0) {
                    list($xw,$xh)= getimagesize($_FILES['ImagenArchivo']['tmp_name'][$k]);
                    if ($ximg==1) $image= imagecreatefromgif($_FILES['ImagenArchivo']['tmp_name'][$k]);
                    elseif ($ximg==2) $image= imagecreatefromjpeg($_FILES['ImagenArchivo']['tmp_name'][$k]);
                    elseif ($ximg==3) $image= imagecreatefrompng($_FILES['ImagenArchivo']['tmp_name'][$k]);
                    if($xh>$xw) {
                        $xw1=(230*$xw)/$xh; $xh1=230;
                    } else {
                        $xw1=230; $xh1=(230*$xh)/$xw;
                    }
                    $image0= imagecreatetruecolor($xw,$xh);
                    imagefill($image0,0,0, imagecolorallocate($image0,255,255,255));
                    imagealphablending($image0, TRUE);
                    imagecopy($image0,$image,0,0,0,0,$xw,$xh);
                    $image1= imagecreatetruecolor($xw1,$xh1);
                    imagefill($image1,0,0, imagecolorallocate($image1,255,255,255));
                    imagealphablending($image1, TRUE);
                    imagecopyresampled($image1,$image,0,0,0,0,$xw1,$xh1,$xw,$xh);
                    if(!empty($imagen)){
                        $f=$fDir.'o'.$imagen.'.jpg'; if(file_exists($f)) unlink($f);
                        $f=$fDir.'a'.$imagen.'.jpg'; if(file_exists($f)) unlink($f);
                    }
                    $imagen=time() + $k;
                    imagejpeg($image0,$fDir.'o'.$imagen.'.jpg',90);
                    imagejpeg($image1,$fDir.'a'.$imagen.'.jpg',90);
                    imagedestroy($image);
                    imagedestroy($image0);
                    imagedestroy($image1);
                }
                if(!empty($imagen)) {
                    $Xi= array('imagen'=>$imagen);
                    if(isset($xdat['ImagenPrincipal'])) if($xdat['ImagenPrincipal']==$k) {
                        $Xi['principal']= 1;
                        $siPrincipal= true;
                        $xdat['tImagen']= $imagen;
                    }
                    $Imagenes[]= $Xi;
                }
            } else if(!empty($I['imagen'])){
                 $f=$fDir.'o'.$I['imagen'].'.jpg'; if(file_exists($f)) unlink($f);
                 $f=$fDir.'a'.$I['imagen'].'.jpg'; if(file_exists($f)) unlink($f);
            }
        }
        if(!empty($Imagenes) and is_array($Imagenes)) {
            if(!$siPrincipal and !empty($Imagenes[0]['imagen'])) {
                $Imagenes[0]['principal']=1;
                $xdat['tImagen']= $Imagenes[0]['imagen'];
            }
            $xdat['tImagenes']= json_encode($Imagenes);
        } else $xdat['tImagenes']=null;
        if(empty($xerr)) {
            $busqueda='';
            if(!empty($xdat['tModelo'])) $busqueda.=' '.$xdat['tModelo'];
            if(!empty($xdat['tMarca'])) {
                $x= $db->getOne("SELECT mNombre FROM grTiendaMarcas WHERE mID=".$xdat['tMarca']);
                $busqueda.=' '.$x;
            } 
            if(!empty($xdat['tNombre'])) $busqueda.=' '.$xdat['tNombre'];
            if(!empty($xdat['tClaves'])) $busqueda.=' '.$xdat['tClaves'];
            $busqueda= trim(grTienda::minusc($busqueda));
            if($xcmd=='agregar') {
                $rq= $db->query("INSERT INTO grTienda VALUES(".$xdat['tID'].",".$xdat['tTipo'].",".$xdat['tCategoria'].",".$xdat['tMarca'].",".$xdat['tImagen'].",".$xdat['tPromocion'].",".self::stxt($xdat['tIdent']).",".self::stxt($xdat['tNombre']).",".self::stxt($xdat['tClaves']).",".self::stxt($xdat['tModelo']).",".self::stxt($xdat['tDescripcion']).",".$xdat['tPrecio'].",".self::stxt($xdat['tPrecios']).",".self::stxt($xdat['tImagenes']).",".self::stxt($busqueda).",".time().",".$xdat['tVipStatus'].",".$xdat['tVipDescuento'].")");
            } else {
                $rq= $db->query("UPDATE grTienda SET tTipo=".$xdat['tTipo'].",tCategoria=".$xdat['tCategoria'].",tMarca=".$xdat['tMarca'].",tImagen=".$xdat['tImagen'].",tPromocion=".$xdat['tPromocion'].",tIdent=".self::stxt($xdat['tIdent']).",tNombre=".self::stxt($xdat['tNombre']).",tClaves=".self::stxt($xdat['tClaves']).",tModelo=".self::stxt($xdat['tModelo']).",tDescripcion=".self::stxt($xdat['tDescripcion']).",tPrecio=".$xdat['tPrecio'].",tPrecios=".self::stxt($xdat['tPrecios']).",tImagenes=".self::stxt($xdat['tImagenes']).",tBusqueda=".self::stxt($busqueda).",tActualiz=".time().",tVipStatus=".$xdat['tVipStatus'].",tVipDescuento=".$xdat['tVipDescuento']." WHERE tID=".$xdat['tID']);
            }
            $this->redirect(self::aDir .'?m=tienda_actualizada');            
        }
        $this->set('xerr',$xerr);
        $this->set('xcmd',$xcmd);
        $this->set('xdat',$xdat);
    }

    public function vip() {
        $xerr= null;
        $db= Loader::db();
        if(!empty($_POST)) {
            if(empty($_SERVER['HTTP_REFERER'])) $this->redirect('/');
            $ref=parse_url($_SERVER['HTTP_REFERER']);
            if($_SERVER['HTTP_HOST']!=$ref['host']) $this->redirect('/');
            foreach($_POST as $k => $v) { if(is_string($v)) $_POST[$k]=trim($v); }
            $xcmd = $_POST['xcmd'];
            $xdat = $_POST;
            if(empty($xdat['tVipStatus'])) $xdat['tVipStatus']=0;
            if(empty($xdat['tVipStatus']) OR empty($xdat['tVipDescuento'])) $xdat['tVipDescuento']=0.0;
            $xdat['tVipDescuento']= floatval($xdat['tVipDescuento']);
            if($xdat['tVipDescuento']<0 OR $xdat['tVipDescuento']>100) $xerr[]= 'El Porcentaje de descuento debe ser entre 0 y 100';
            if(empty($xerr)) {
                $rq= $db->query("UPDATE grTienda SET tVipStatus=".$xdat['tVipStatus'].",tVipDescuento=".$xdat['tVipDescuento'].",tActualiz=".time()." WHERE tID=0");
                $this->redirect('/_admin/?m=descuento_vip_actualizado');
            }
            $this->set('xcmd',$xcmd);
            $this->set('xdat',$xdat);
        } else {
            $rq= $db->query("SELECT * FROM grTienda WHERE tID=0");
            if (!($rx=$rq->fetchrow())) $this->redirect(self::aDir);
            $this->set('xdat',$rx);
        }
        $this->set('xerr',$xerr);
    }

}
?>