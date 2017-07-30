<?php   defined("C5_EXECUTE") or die(_("Access Denied."));

Class TiendaComprasController Extends Controller {
    
    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }
    
    function o( $xcmd=null, $xid=null, $xinfo=null ) {
     if(!empty($_SERVER['HTTP_REFERER'])) if(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == $_SERVER['HTTP_HOST']) {        
        Loader::library('grCompras','gr_tienda');
        if(!empty($_POST['id'])) $xid = $_POST['id'];
        if(!empty($xid))
            if($xcmd=='comprar') {
                $cant = intval($xinfo);
                if(!empty($_POST['cantidad'])) $cant = intval($_POST['cantidad']);
                if(empty($cant)) $cant=1;
                grCompras::comprar($xid,$cant);
            } elseif($xcmd=='eliminar') grCompras::eliminar($xid);        
     }
     $this->redirect('/tienda/compras/'); 
    }
    
    public function enviar() {
        if(empty($_SERVER['HTTP_REFERER'])) $this->redirect('/');
        $ref=parse_url($_SERVER['HTTP_REFERER']);
        if($_SERVER['HTTP_HOST']!=$ref['host']) $this->redirect('/');
        if(empty($_POST)) $this->redirect('/?e=enviar_no_post');
        Loader::library('grCompras','gr_tienda');
        $Compras = grCompras::compras();
        if($Compras['numCompras']<1) $this->redirect('/tienda/?msg=enviar_comprar');
        $xdat=$_POST;
        if(empty($xdat['Cliente'])) $this->redirect('/?e=enviar_no_cliente');
        $db = Loader::db();
        $rq= $db->query('INSERT INTO grRegistroCompras VALUES('.time().','.$xdat['Cliente'].','. self::stxt(json_encode($xdat)) .')');
        $oc="\nNúmero : ".$xdat['Orden']."\nTOTAL a pagar : $".number_format($Compras['total'],2)."\n \nCompras:";
        foreach($Compras['compras'] as $X) {
            $oc.= "\n-----\n".$X['cantidad'];
            if(!empty($X['unidad'])) $oc.= ' '.$X['unidad'];
            $oc.= ' : '.$X['nombre']."\n";
            $x='';
            if(!empty($X['modelo'])) $x.='mod. '.$X['modelo'];
            if(!empty($X['marca'])) $x.=' '.$X['marca'];
            if(!empty($x)) $oc.= trim($x)."\n";
            $oc.= '$'.number_format($X['subtotal'],2);
            if(!empty($X['promocion'])) $oc.= ' '.$X['promocion'];
        }
        $oc.="\n-----\n";
        $xdat['OrdenCompra']=$oc;
        $xdat['xtitulos']['OrdenCompra']='Orden de Compra'; 
        if(empty($xdat['Factura'])){
            unset($xdat['RFC']);
            unset($xdat['NombreFac']);
            unset($xdat['CorreoFac']);
            unset($xdat['DireccionFac']);
            unset($xdat['ColoniaFac']);
            unset($xdat['CiudadFac']);
            unset($xdat['MunicipioFac']);
            unset($xdat['EstadoFac']);
            unset($xdat['CodigoPostalFac']);
        }
        $rq= $db->query('INSERT INTO grTiendaOrdenes VALUES('.time().','.$xdat['Cliente'].','. self::stxt($xdat['CorreoElectronico']).','. self::stxt($xdat['Orden']).','.$Compras['total'].','. self::stxt($xdat['xcmd']).",'',". self::stxt(json_encode($xdat)) .")");

        Loader::model('grForma','gr_forma');
        $Config = grForma::Config();
        $redirect='/forma/enviada/';
        if(!empty($Config['redirect'])) $redirect=$Config['redirect'];
        if(!empty($xdat['xredirect'])) {$redirect=$xdat['xredirect'];unset($xdat['xredirect']);}
        if(substr($redirect, -1)!='/') $redirect.='/';
        grForma::Enviar($xdat);
        if($xdat['xcmd']=='pago') {
            $_SESSION['grPago']= $xdat;
            $this->redirect('/pago/paypal/compras/');
        }
        grCompras::nuevo();
        header('Location: '.$redirect.'?msg=forma_enviada');
        exit;
    }
    
    function view($op=null) {
        Loader::library('grRegistro','gr_registro');
        $xrEmail= grRegistro::email();
        if(empty($xrEmail)) $this->redirect('/registro/?msg=compras_registarse');
        Loader::library('grCompras','gr_tienda');
        $Compras = grCompras::compras();
        if($Compras['numCompras']<1) $this->redirect('/tienda/');
        $html = Loader::helper('html');
        $this->addHeaderItem($html->css('compras.css','gr_tienda'));
        $this->addHeaderItem($html->javascript('compras.js','gr_tienda'));
        $this->set('Compras',$Compras);
        $this->set('orden', chr(mt_rand(65,90)) . chr(mt_rand(65,90)) . time() . chr(mt_rand(65,90)));
        $this->set('cliente', grRegistro::id());
        $this->set('xcmd',$op);
    }
    
}
?>