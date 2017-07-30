<?php   defined("C5_EXECUTE") or die(_("Access Denied."));

Class TiendaController Extends Controller {
    
    const aDir = "/tienda/";
    
    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }

    public function view($xfp1=null,$xfp2=null,$xfp3=null) {
        $html = Loader::helper('html');
        $this->addHeaderItem('<style type="text/css">#admin{display:block;padding:0.6em;border-color:#dfdfdf;border-style:solid;border-width:1px;color:#333333;min-height:0;}</style>');
        $db = Loader::db();
        $xcmd=null;
        $xdat=null;
        $xtit=null;
        $xcat=null;
        $xpag=null;
        Loader::library('grRegistro','gr_registro');
        $xrTipo= grRegistro::tipo();
        $sql= 'SELECT * FROM grTienda as t, grTiendaCategorias as c, grTiendaMarcas as m, grTiendaPromociones as p WHERE t.tCategoria=c.cID AND t.tMarca=m.mID AND t.tPromocion=p.pID AND tID>100';
        if($xfp1=='detalle'){
            if(empty($xfp2)) $this->redirect('/?e=sin_articulo');
            if(is_numeric($xfp2)) {
                $rq= $db->query('SELECT * FROM grTienda WHERE tID='.$xfp2);
                if($X=$rq->fetchrow()) {
                    $r= 'detalle/'.$X['tIdent'].'/';
                    if(!empty($xfp3)) $r.= $xfp3.'/';
                    $this->redirect(self::aDir.$r);
                } else $this->redirect('/?e=id_detalle');
            }
            if($xrTipo>0) $sql.=' AND tTipo>=0'; else $sql.=' AND tTipo=0';
            $sql.=' AND tIdent='.self::stxt($xfp2);
            $rq= $db->query($sql);
            if($xdat=$rq->fetchrow()){
                $xcmd='detalle';
                $xtit=$xdat['tNombre'];
                if(!empty($xdat['tModelo'])) { if(!empty($xtit)) $xtit.=' : '; $xtit.= 'Modelo '.$xdat['tModelo']; }
            } else $this->redirect('/?e=no_identificacion_detalle');
        }
        elseif($xfp1=='categoria'){
            $xcmd='categoria';
            if(empty($xfp2)) $this->redirect('/tienda/?e=sin_categoria');
            if(is_numeric($xfp2)) {
                $rq= $db->query('SELECT * FROM grTiendaCategorias WHERE cID='.$xfp2);
                if($X=$rq->fetchrow()){
                    $r= 'categoria/'.$X['cIdent'].'/';
                    if(!empty($xfp3)) $r.= $xfp3.'/';
                    $this->redirect(self::aDir.$r);
                } else $this->redirect('/tienda/?e=id_categoria');
            }
            if($xrTipo>0) $sql.=' AND tTipo>=0'; else $sql.=' AND tTipo=0';
            $sql.=' AND c.cIdent='.self::stxt($xfp2).' ORDER BY tActualiz DESC, tID DESC';
            $rq= $db->query($sql);
            while($X=$rq->fetchrow()) $xdat[]=$X;
            if(empty($xdat)) $this->redirect('/tienda/e=ident_categoria');
            $xtit=$xdat[0]['cNombre'];
        }
        elseif($xfp1=='marcas'){
            $xcmd='marcas';
            $xtit='Todos los Artículos';
            if($xrTipo>0) $sql.=' AND tTipo>=0'; else $sql.=' AND tTipo=0';
            $sql.=' AND t.tMarca';
            if(!empty($xfp2)) {
                $rq= $db->query("SELECT * FROM grTiendaMarcas WHERE mIdent=".self::stxt($xfp2));
                if($X=$rq->fetchrow()) {
                    $sql.= '='.$X['mID'];
                    $xtit= $X['mNombre']; 
                } else $sql.= '>0';
            } else $sql.= '>0';
            $rq= $db->query($sql.' ORDER BY tActualiz DESC, tID DESC');
            while($X=$rq->fetchrow()) $xdat[]=$X;
            if(empty($xdat)) $this->redirect('/tienda/');
        }
        elseif($xfp1=='promociones'){
            $xcmd='promociones';
            $xtit='Todos los Artículos';
            if($xrTipo>0) $sql.=' AND tTipo>=0'; else $sql.=' AND tTipo=0';
            $sql.=' AND t.tPromocion';
            if(!empty($xfp2)) {
                $rq= $db->query("SELECT * FROM grTiendaPromociones WHERE pIdent=".self::stxt($xfp2));
                if($X=$rq->fetchrow()) {
                    $sql.= '='.$X['pID'];
                    $xtit= $X['pNombre']; 
                } else $sql.= '>0';
            } else $sql.= '>0';
            $rq= $db->query($sql.' ORDER BY tActualiz DESC, tID DESC');
            while($X=$rq->fetchrow()) $xdat[]=$X;
            if(empty($xdat)) $this->redirect('/tienda/');
        }
        elseif($xfp1=='vip'){
            if($xrTipo<1) $this->redirect('/tienda/novedades/');
            $xcmd='vip';
            $xtit='VIP';
            $sql.=' AND t.tTipo>0';
            $rq= $db->query($sql.' ORDER BY tActualiz DESC, tID DESC');
            while($X=$rq->fetchrow()) $xdat[]=$X;
        }
        elseif($xfp1=='novedades'){
            $xcmd='novedades';
            if($xrTipo>0) $sql.=' AND tTipo>=0'; else $sql.=' AND tTipo=0';
            $sql.=' ORDER BY tActualiz DESC, tID DESC LIMIT 20';
            $rq= $db->query($sql);
            while($X=$rq->fetchrow()) $xdat[]=$X;
            if(empty($xdat)) $this->redirect('/');
            shuffle($xdat);
        }
        elseif($xfp1=='buscar'){
            $xcmd='buscar';
            Loader::model('grTienda','gr_tienda');
            $buscar= null;
            if(!empty($_POST['Buscar'])){
                $buscar= $_POST['Buscar'];
                $_SESSION['grTiendaBuscar']= $buscar;
            } elseif(!empty($_SESSION['grTiendaBuscar'])) $buscar=$_SESSION['grTiendaBuscar'];
            $B= grTienda::parabuscar($buscar);
            if(empty($B)) $this->redirect('/?e=nada_que_buscar');
            if($xrTipo>0) $sql.=' AND tTipo>=0'; else $sql.=' AND tTipo=0';
            $xbuscar='';
            foreach($B as $k=>$b){
                if($k>5) break;
                $sql.= " AND tBusqueda LIKE '%".$b."%'";
                $xbuscar.=$b.' ';
            }
            $rq= $db->query($sql.' ORDER BY tActualiz DESC, tID DESC');
            while($X=$rq->fetchrow()) $xdat[]=$X;
            $this->set('xbuscar',$xbuscar);
        }
        else {
            if($xrTipo>0) $sql.=' AND tTipo>=0'; else $sql.=' AND tTipo=0';
            $rq= $db->query($sql.' ORDER BY tActualiz DESC, tID DESC');
            while($X=$rq->fetchrow()) $xdat[]=$X;
            if(empty($xdat)) $this->redirect('/');
            $xcmd='tienda';
            $xtit='Todos los Artículos';
        }
        $this->addHeaderItem($html->css('fancyzoom.css','gr_tienda'));
        $this->addHeaderItem($html->javascript('jquery.fancyzoom.js','gr_tienda'));
        $this->addHeaderItem('<script type="text/javascript">$(document).ready(function(){ $(\'a.fzoom\').fancyZoom(); });</script>', 'CONTROLLER');
        if($xcmd=='detalle') {
            $this->addHeaderItem($html->css('detalle.css','gr_tienda'));
            $this->addHeaderItem($html->javascript('detalle.js','gr_tienda'));
        }
        $this->set('xcmd',$xcmd);
        $this->set('xdat',$xdat);
        $this->set('xtit',$xtit);
        $this->set('xcat',$xcat);
    }

}
?>