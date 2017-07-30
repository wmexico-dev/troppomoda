<?php defined("C5_EXECUTE") or die(_("Access Denied."));

Class AdminTiendaCategoriasController Extends Controller {

    const aDir= "/_admin/tienda/categorias/";
    
    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }
    private function mayusc($x=null) { return trim(strtr(strtoupper($x),'áéíóúüñ','ÁÉÍÓÚÜÑ')); }

    public function on_start() {
        $this->set('xerr',null);
    }
    
    public function agregar() {
        $xdat= array('cID'=>0);
        $this->set('xdat',$xdat);
    }
    
    public function editar($id=0) {
        $id= intval($id);
        $db= Loader::db();
        $rq= $db->query("SELECT *, (SELECT count(tCategoria) FROM grTienda WHERE tCategoria=".$id.") as numTienda FROM grTiendaCategorias WHERE cID=".$id);
        if(!($rx=$rq->fetchrow())) $this->redirect(self::aDir .'?e=editar_no_id');
        $this->set('xdat',$rx);
        if(!empty($rx['numTienda'])) $this->addHeaderItem('<script type="text/javascript">function eliminar() { alert("No se puede eliminar una Categoría con artículos asignados."); }</script>', 'CONTROLLER');
        else $this->addHeaderItem('<script type="text/javascript">function eliminar() { var resp= confirm("¿Desea eliminar la Categoría?"); if(resp) { document.forma.xcmd.value= \'eliminar\'; document.forma.submit(); }}</script>', 'CONTROLLER');
    }

    public function actualizar() {
        foreach($_POST as $k=> $v) { if(is_string($v)) $_POST[$k]=trim($v); }
        $xerr= null;
        if(empty($_POST['xcmd'])) $this->redirect(self::aDir .'?e=actualizar_no_xcmd');
        $xcmd= $_POST['xcmd'];
        $xdat= $_POST;
        if($xcmd=='agregar') $xdat['cID']=time();
        if(empty($xdat['cID'])) $this->redirect(self::aDir .'?e=actualizar_no_id');
        $db= Loader::db();
        if($xcmd=='eliminar') {
            $rq= $db->query("DELETE FROM grTiendaCategorias WHERE cID=".$xdat['cID']);
            $this->redirect(self::aDir .'?m=eliminar_ok');
        }
        if(empty($xdat['cTipo']) or !is_numeric($xdat['cTipo'])) $xdat['cTipo']=0;
        if(empty($xdat['cIndice']) or !is_numeric($xdat['cIndice'])) $xdat['cIndice']=0;
        if(empty($xdat['cCategoria']) or !is_numeric($xdat['cCategoria'])) $xdat['cCategoria']=0;
        if(empty($xdat['cNombre'])) $xerr[]='Falta el Nombre de la Categoría';
        else {
            Loader::model('grTienda','gr_tienda');
            if(empty($xdat['cIdent'])) $xdat['cIdent']= grTienda::ident($xdat['cNombre']);
             else $xdat['cIdent']= grTienda::ident($xdat['cIdent']);
            $id= $db->getOne("SELECT cID FROM grTiendaCategorias WHERE cIdent=".self::stxt($xdat['cIdent']));
            if(!empty($id)) if($id!=$xdat['cID']) $xerr[]='Ya existe esa Identificación';
        }
        if(empty($xerr)) {
            if($xcmd=='agregar') {
                $sql= "INSERT INTO grTiendaCategorias VALUES(".$xdat['cID'].",0,".$xdat['cTipo'].",0,".self::stxt($xdat['cIdent']).",".self::stxt($xdat['cNombre']).");";
            } else {  //editar
                $sql= "UPDATE grTiendaCategorias SET cTipo=".$xdat['cTipo'].",cCategoria=".$xdat['cCategoria'].",cIdent=".self::stxt($xdat['cIdent']).",cNombre=".self::stxt($xdat['cNombre'])." WHERE cID=".$xdat['cID'];
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