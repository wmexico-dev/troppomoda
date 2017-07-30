<?php defined("C5_EXECUTE") or die(_("Access Denied."));

Class AdminTiendaMarcasController Extends Controller {

    const aDir= "/_admin/tienda/marcas/";
    
    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }
    private function mayusc($x=null) { return trim(strtr(strtoupper($x),'áéíóúüñ','ÁÉÍÓÚÜÑ')); }

    public function on_start() {
        $this->set('xerr',null);
    }
    
    public function agregar() {
        $xdat= array('mID'=>0);
        $this->set('xdat',$xdat);
    }
    
    public function editar($id=0) {
        $id= intval($id);
        $db= Loader::db();
        $rq= $db->query("SELECT *, (SELECT count(tMarca) FROM grTienda WHERE tMarca=".$id.") as numTienda FROM grTiendaMarcas WHERE mID=".$id);
        if(!($rx=$rq->fetchrow())) $this->redirect(self::aDir .'?e=editar_no_id');
        $this->set('xdat',$rx);
        if(!empty($rx['numTienda'])) $this->addHeaderItem('<script type="text/javascript">function eliminar() { alert("No se puede eliminar una Marca con artículos asignados."); }</script>', 'CONTROLLER');
        else $this->addHeaderItem('<script type="text/javascript">function eliminar() { var resp= confirm("¿Desea eliminar la Marca?"); if(resp) { document.forma.xcmd.value= \'eliminar\'; document.forma.submit(); }}</script>', 'CONTROLLER');
    }
	
	public function actualizar() {
        foreach($_POST as $k=> $v) { if(is_string($v)) $_POST[$k]=trim($v); }
        $xerr= null;
        if(empty($_POST['xcmd'])) $this->redirect(self::aDir .'?e=actualizar_no_xcmd');
        $xcmd= $_POST['xcmd'];
        $xdat= $_POST;
        if($xcmd=='agregar') $xdat['mID']=time();
        if(empty($xdat['mID'])) $this->redirect(self::aDir .'?e=actualizar_no_id');
        $db= Loader::db();
		if($xcmd=='eliminar') {
			$rq= $db->query("DELETE FROM grTiendaMarcas WHERE mID=".$xdat['mID']);
            $this->redirect(self::aDir .'?m=eliminar_ok');
		}
        if(empty($xdat['mTipo']) or !is_numeric($xdat['mTipo'])) $xdat['mTipo']=0;
        if(empty($xdat['mIndice']) or !is_numeric($xdat['mIndice'])) $xdat['mIndice']=0;
        if(empty($xdat['mImagen']) or !is_numeric($xdat['mImagen'])) $xdat['mImagen']=0;
		if(empty($xdat['mNombre'])) $xerr[]='Falta el Nombre de la Marca';
        else {
            if(empty($xdat['mIdent'])) {
                Loader::model('grTienda','gr_tienda');
                $xdat['mIdent']= grTienda::ident($xdat['mNombre']);
            } 
            $id= $db->getOne("SELECT mID FROM grTiendaMarcas WHERE mIdent=".self::stxt($xdat['mIdent']));
            if(!empty($id)) if($id!=$xdat['mID']) $xerr[]='Ya existe esa Identificación';
        }
        
        if(empty($xdat['mVipStatus'])) $xdat['mVipStatus']=0;
        if(empty($xdat['mVipStatus']) OR empty($xdat['mVipDescuento'])) $xdat['mVipDescuento']=0.0;
        $xdat['mVipDescuento']= floatval($xdat['mVipDescuento']);
        if($xdat['mVipDescuento']<0 OR $xdat['mVipDescuento']>100) $xerr[]= 'El Porcentaje de descuento debe ser entre 0 y 100';
        if(empty($xerr)) {
    		if($xcmd=='agregar') {
    			$sql= "INSERT INTO grTiendaMarcas VALUES(".$xdat['mID'].",0,".$xdat['mTipo'].",".$xdat['mImagen'].",".self::stxt($xdat['mIdent']).",".self::stxt($xdat['mNombre']).",".$xdat['mVipStatus'].",".$xdat['mVipDescuento'].");";
    		} else {
    			$sql= "UPDATE grTiendaMarcas SET mNombre=".self::stxt($xdat['mNombre']).",mIdent=".self::stxt($xdat['mIdent']).",mVipStatus=".$xdat['mVipStatus'].",mVipDescuento=".$xdat['mVipDescuento']." WHERE mID=".$xdat['mID'];
    		}
    		$rq= $db->query($sql);
            $this->redirect(self::aDir .'?m=actualizar_ok');
        }
        $this->set('xerr',$xerr);
        $this->set('xcmd',$xcmd);
        $this->set('xdat',$xdat);
	}
	
}
?>