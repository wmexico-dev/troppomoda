<?php defined("C5_EXECUTE") or die(_("Access Denied."));

Class AdminTiendaPromocionesController Extends Controller {

    const aDir= "/_admin/tienda/promociones/";

    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }

    public function on_start() {
        $this->set('xerr',null);
        $html = Loader::helper('html');
        if ($this->getTask()!='view') {
            $this->addHeaderItem($html->css('colorPicker.min.css','gr_tienda'));
            $this->addHeaderItem($html->javascript('colorPicker.min.js','gr_tienda'));
            $this->addHeaderItem($html->javascript('colorPicker.js','gr_tienda'));
        }
    }
    
    public function agregar() {
        $xdat= array('pID'=>0);
        $this->set('xdat',$xdat);
    }
    
    public function editar($id=0) {
        $id= intval($id);
        $db= Loader::db();
        $rq= $db->query("SELECT *, (SELECT count(tPromocion) FROM grTienda WHERE tPromocion=".$id.") as numTienda FROM grTiendaPromociones WHERE pID=".$id);
        if(!($rx=$rq->fetchrow())) $this->redirect(self::aDir .'?e=editar_no_id');
        $this->set('xdat',$rx);
        $this->addHeaderItem('<script type="text/javascript">function eliminar() { var resp= confirm("¿Desea eliminar la Promoción?"); if(resp) { document.forma.xcmd.value= \'eliminar\'; document.forma.submit(); }}</script>', 'CONTROLLER');
    }

    public function actualizar() {
        foreach($_POST as $k=> $v) { if(is_string($v)) $_POST[$k]=trim($v); }
        $xerr= null;
        if(empty($_POST['xcmd'])) $this->redirect(self::aDir .'?e=actualizar_no_xcmd');
        $xcmd= $_POST['xcmd'];
        $xdat= $_POST;
        if($xcmd=='agregar') $xdat['pID']=time();
        if(empty($xdat['pID'])) $this->redirect(self::aDir .'?e=actualizar_no_id');
        $db= Loader::db();
        if($xcmd=='eliminar') {
            $rq= $db->query('UPDATE grTienda SET tPromocion=0 WHERE tPromocion='.$xdat['pID']);
            $rq= $db->query('DELETE FROM grTiendaPromociones WHERE pID='.$xdat['pID']);
            $this->redirect(self::aDir .'?m=eliminar_ok');
        }
        if(empty($xdat['pTipo']) or !is_numeric($xdat['pTipo'])) $xdat['pTipo']=0;
        if(empty($xdat['pIndice']) or !is_numeric($xdat['pIndice'])) $xdat['pIndice']=0;
        if(empty($xdat['pImagen']) or !is_numeric($xdat['pImagen'])) $xdat['pImagen']=0;
        if(empty($xdat['pNombre'])) $xerr[]='Falta el Nombre de la Promoción';
        else {
            if(empty($xdat['pIdent'])) {
                Loader::model('grTienda','gr_tienda');
                $xdat['pIdent']= grTienda::ident($xdat['pNombre']);
            } 
            $id= $db->getOne("SELECT pID FROM grTiendaPromociones WHERE pIdent=".self::stxt($xdat['pIdent']));
            if(!empty($id)) if($id!=$xdat['pID']) $xerr[]='Ya existe esa Identificación';
        }
        
        if(empty($xdat['pVipStatus'])) $xdat['pVipStatus']=0;
        if(empty($xdat['pVipStatus']) OR empty($xdat['pVipDescuento'])) $xdat['pVipDescuento']=0.0;
        $xdat['pVipDescuento']= floatval($xdat['pVipDescuento']);
        if($xdat['pVipDescuento']<0 OR $xdat['pVipDescuento']>100) $xerr[]= 'El Porcentaje de descuento debe ser entre 0 y 100';
        if(empty($xerr)) {
            if($xcmd=='agregar') {
                $rq= $db->query("INSERT INTO grTiendaPromociones VALUES(".$xdat['pID'].",0,".$xdat['pTipo'].",".$xdat['pImagen'].",".self::stxt($xdat['pColor']).",".self::stxt($xdat['pIdent']).",".self::stxt($xdat['pNombre']).",".$xdat['pVipStatus'].",".$xdat['pVipDescuento'].")");
            } else {
                $rq= $db->query("UPDATE grTiendaPromociones SET pIdent=".self::stxt($xdat['pIdent']).",pImagen=".$xdat['pImagen'].",pColor=".self::stxt($xdat['pColor']).",pNombre=".self::stxt($xdat['pNombre']).",pVipStatus=".$xdat['pVipStatus'].",pVipDescuento=".$xdat['pVipDescuento']." WHERE pID=".$xdat['pID']);
            }
            $this->redirect(self::aDir .'?m=actualizar_ok');            
        }
        $this->set('xerr',$xerr);
        $this->set('xcmd',$xcmd);
        $this->set('xdat',$xdat);
    }

}
?>