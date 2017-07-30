<?php   defined("C5_EXECUTE") or die(_("Access Denied."));

Class AdminImagenesCategoriasController Extends Controller {

    const aDir = "/_admin/imagenes/categorias/";
    
    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }

    public function on_start() {
        $html = Loader::helper('html');
        $this->set('xerr',null);
    }
    
    public function agregar() {
        $xdat = array('cID'=>0);
        $this->set('xdat',$xdat);
    }
    
    public function editar($id=0) {
        $id = (integer)$id;
        $db = Loader::db();
        $rq = $db->query("SELECT *, (SELECT count(cID) FROM grImagenes WHERE cID=".$id.") as numImagenes FROM grImagenesCategorias WHERE cID=".$id);
        if (!($rx=$rq->fetchrow())) $this->redirect(self::aDir);
        $this->set('xdat',$rx);
        if(!empty($rx['numImagenes'])) $this->addHeaderItem('<script type="text/javascript">function eliminar() { alert("No se puede eliminar una Categoría con entradas asignados."); }</script>', 'CONTROLLER');
        else $this->addHeaderItem('<script type="text/javascript">function eliminar() { var resp = confirm("¿Desea eliminar la Categoría?"); if (resp) { document.forma.xcmd.value = \'eliminar\'; document.forma.submit(); }}</script>', 'CONTROLLER');
    }

    public function actualizar() {
        foreach($_POST as $k => $v) { if(is_string($v)) $_POST[$k]=trim($v); }
        $xerr = null;
        if(empty($_POST['xcmd'])) $this->redirect(self::aDir .'?e=actualizar_no_xcmd');
        $xcmd = $_POST['xcmd'];
        $xdat = $_POST;
        if($xcmd=='agregar') $xdat['cID']=time();
        if(empty($xdat['cID'])) $this->redirect(self::aDir .'?e=actualizar_no_id');
        $db = Loader::db();
        if($xcmd=='eliminar') {
            $rq = $db->query("DELETE FROM grImagenesCategorias WHERE cID=".$xdat['cID']);
            $this->redirect(self::aDir .'?m=eliminar_ok');
        }
        if(empty($xdat['cNombre'])) $xerr[]='Falta el Nombre de la Categoría';
        if(empty($xdat['cIndice']) or !is_numeric($xdat['cIndice'])) $xdat['cIndice']=0;
        if(empty($xdat['cMaxAncho']) or !is_numeric($xdat['cMaxAncho'])) $xdat['cMaxAncho']=0;
        if(empty($xdat['cMaxAltura']) or !is_numeric($xdat['cMaxAltura'])) $xdat['cMaxAltura']=0;
        if(empty($xdat['cIdent'])) {
            Loader::model('grImagenes','gr_imagenes');
            $xdat['cIdent']= grImagenes::ident($xdat['cNombre']);
        }
        if(empty($xdat['cIdent'])) $xerr[]='Falta la Identificación';
        else {
            $rq = $db->query("SELECT cID FROM grImagenesCategorias WHERE cIdent=".self::stxt($xdat['cIdent']));
            if($rx=$rq->fetchrow()) { if($rx['cID']!=$xdat['cID']) $xerr[]='Ya existe esa Identificación'; }
        }
        if(empty($xerr)) {
            if($xcmd=='agregar') {
                $sql = "INSERT INTO grImagenesCategorias VALUES(".$xdat['cID'].','.$xdat['cIndice'].','.self::stxt($xdat['cIdent']).','.self::stxt($xdat['cNombre']).','.$xdat['cMaxAncho'].','.$xdat['cMaxAltura'].','.self::stxt($xdat['cStyle']).','.self::stxt($xdat['cNota']).")";
            } else {
                $sql = "UPDATE grImagenesCategorias SET cIdent=".self::stxt($xdat['cIdent']).",cNombre=".self::stxt($xdat['cNombre']).",cMaxAncho=".$xdat['cMaxAncho'].",cMaxAltura=".$xdat['cMaxAltura'].",cStyle=".self::stxt($xdat['cStyle']).",cNota=".self::stxt($xdat['cNota'])." WHERE cID=".$xdat['cID'];
            }
            $rq = $db->query($sql);
            $this->redirect(self::aDir .'?m=actualizar_ok');
        }
        $this->set('xerr',$xerr);
        $this->set('xcmd',$xcmd);
        $this->set('xdat',$xdat);
    }

}
?>