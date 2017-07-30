<?php defined("C5_EXECUTE") or die(_("Access Denied."));

Class AdminRegistroController Extends Controller {

    const aDir= "/_admin/registro/";

    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }

    public function on_start() {
        $html= Loader::helper('html');
        $db = Loader::db();
        $t= time();
        $rq = $db->query("UPDATE grRegistro SET rTipo=0 WHERE rID>0 AND rTipo>0 AND rVipFin<".$t);
        $t-= (60 * 24 * 60 * 60);
        $rq = $db->query("DELETE FROM grRegistro WHERE rID>0 AND rID<".$t." AND rClave='' AND rLogin=0");
        if ($this->getTask()=='editarRegistro' or $this->getTask()=='actualizar') {
            $this->addHeaderItem($html->css("jquery.ui.css"));
            $this->addHeaderItem($html->javascript('jquery.form.js'));
            $this->addHeaderItem($html->javascript("jquery.ui.js"));
            $this->addHeaderItem("<script type=\"text/javascript\">$(document).ready(function(){
                $('#xVipFin').datepicker({ dateFormat: 'yy-mm-dd', changeYear: true });
                $('#xVipFinB0').click(function(){ $('#xVipFin0').show(); $('#xVipFin1').hide(); document.forma.xVipFinS.value = 0; });
                $('#xVipFinB1').click(function(){ $('#xVipFin1').show(); $('#xVipFin0').hide(); document.forma.xVipFinS.value = 1; });
                if (xvf) { $('#xVipFin1').show(); $('#xVipFin0').hide(); document.forma.xVipFinS.value = 1; } else { $('#xVipFin0').show(); $('#xVipFin1').hide(); document.forma.xVipFinS.value = 0; }
                $('#eliminar').click(function(){ var resp = confirm('¿Desea eliminar el Registro?'); if (resp) { document.forma.xcmd.value = 'eliminar'; document.forma.submit(); } });
            });</script>",'CONTROLLER');
        }
        $this->set('xerr',null);
        $this->set('xdat',null);
        $this->set('xcmd',null);
    }
    
    public function clave() {
        if(!empty($_POST)) {
            if(empty($_SERVER['HTTP_REFERER'])) $this->redirect(self::aDir);
            $ref=parse_url($_SERVER['HTTP_REFERER']);
            if($_SERVER['HTTP_HOST']!=$ref['host']) $this->redirect(self::aDir);
            foreach($_POST as $k => $v) { if(is_string($v)) $_POST[$k]=trim($v); }
            $xcmd = $_POST['xcmd'];
            $xerr= null;
            $xdat = $_POST;
            if(empty($xdat['rClave'])) $xerr[]='Falta la nueva Clave';
            if(empty($xerr)) {
                $db = Loader::db();
                $clave= md5($xdat['rClave']);
                $rq = $db->query("UPDATE grRegistro SET rClave=".self::stxt($clave)." WHERE rID=0");
                $this->redirect(self::aDir .'?m=clave_actualizada');
            }
            $this->set('xerr',$xerr);
            $this->set('xcmd',$xcmd);
            $this->set('xdat',$xdat);
        }
    }
    
    public function editar() {
        if(!empty($_POST)) {
            if(empty($_SERVER['HTTP_REFERER'])) $this->redirect(self::aDir);
            $ref=parse_url($_SERVER['HTTP_REFERER']);
            if($_SERVER['HTTP_HOST']!=$ref['host']) $this->redirect(self::aDir);
            foreach($_POST as $k => $v) { if(is_string($v)) $_POST[$k]=trim($v); }
            $xcmd = $_POST['xcmd'];
            $xerr= null;
            $xdat = $_POST;
            if(empty($xdat['rEmail'])) $xerr[]='Falta el correo del registro';
            $db = Loader::db();
            $id= $db->getOne('SELECT rID FROM grRegistro WHERE rEmail='.self::stxt($xdat['rEmail']));
            if(empty($id)) $xerr[]='No existe el correo en el registro';
            if(empty($xerr)) $this->redirect(self::aDir .'editarRegistro/'.$id);
            $this->set('xerr',$xerr);
            $this->set('xcmd',$xcmd);
            $this->set('xdat',$xdat);
        }
    }
    
    public function editarRegistro($id=0) {
        if(empty($id)) $this->redirect(self::aDir);
        if(empty($_SERVER['HTTP_REFERER'])) $this->redirect(self::aDir);
        $ref=parse_url($_SERVER['HTTP_REFERER']);
        if($_SERVER['HTTP_HOST']!=$ref['host']) $this->redirect(self::aDir);
        $db = Loader::db();
        $rq = $db->query("SELECT * FROM grRegistro WHERE rID=".$id);
        if (!($rx=$rq->fetchrow())) $this->redirect(self::aDir);
        $this->set('xdat',$rx);
    }
    
    public function actualizar() {
        if(empty($_POST)) $this->redirect(self::aDir);
        if(empty($_SERVER['HTTP_REFERER'])) $this->redirect(self::aDir);
        $ref=parse_url($_SERVER['HTTP_REFERER']);
        if($_SERVER['HTTP_HOST']!=$ref['host']) $this->redirect(self::aDir);
        foreach($_POST as $k => $v) { if(is_string($v)) $_POST[$k]=trim($v); }
        $xcmd = $_POST['xcmd'];
        $xerr= null;
        $xdat = $_POST;
        $db = Loader::db();
        if($xcmd=='eliminar') {
            $rq = $db->query("DELETE FROM grRegistro WHERE rID=".$xdat['rID']);
            $rq = $db->query("DELETE FROM grRegistroCompras WHERE rID=".$xdat['rID']);
            $this->redirect(self::aDir .'?m=registro_eliminado');
        }
        if(empty($xdat['rTipo'])) $xdat['rTipo']=0;
        if(empty($xdat['rVipFin'])) $xdat['rVipFin']=0;
        if(empty($xdat['xVipFinS'])) $xdat['rVipFin']=0;
            else {
                $D= date_parse($xdat['xVipFin']);
                $xdat['rVipFin']= mktime(23,59,0,$D['month'],$D['day'],$D['year']);
            }
        if($xdat['rTipo']>0 and empty($xdat['rVipFin'])) $xerr[]= 'Un registro VIP debe tener Fecha de vencimiento';
        if(empty($xerr)){
            $rq = $db->query("UPDATE grRegistro SET rTipo=".$xdat['rTipo'].",rVipFin=".$xdat['rVipFin'].",rVipInfo=".self::stxt($xdat['rVipInfo'])." WHERE rID=".$xdat['rID']);
            $this->redirect(self::aDir .'editar/?m=registro_actualizado');
        }
        $rq = $db->query("SELECT * FROM grRegistro WHERE rID=".$xdat['rID']);
        if (!($rx=$rq->fetchrow())) $this->redirect(self::aDir);
        foreach($xdat as $l=>$v) $rx[$l]=$v;
        $this->set('xerr',$xerr);
        $this->set('xcmd',$xcmd);
        $this->set('xdat',$rx);
    }
    
}