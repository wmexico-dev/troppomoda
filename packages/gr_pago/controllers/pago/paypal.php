<?php   defined("C5_EXECUTE") or die(_("Access Denied."));

Class PagoPaypalController Extends Controller {
    
    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }
    
    public function compras($x=null) {
        if(empty($_SESSION['grPago']['Orden'])) $this->redirect('/e=paypal_no_orden');
        $Pago = $_SESSION['grPago'];
        $db = Loader::db();
        $t = explode (' ', microtime());
        $ID = $t[1].substr($t[0],2,4);
        $rq= $db->query('INSERT INTO grPago VALUES('.$ID.','.$Pago['Cliente'].','. self::stxt($Pago['CorreoElectronico']).','. self::stxt($Pago['Orden']).','. $Pago['OrdenTotal'].','. self::stxt($Pago['xcmd']).",'',". self::stxt(json_encode($Pago)) .")");
        Loader::model('grPaypal','gr_pago');
        $Config = grPaypal::Config();
        $Info = $Config['info'];
        $Info['amount'] = $Pago['OrdenTotal'];
        $Info['item_name'] = "Orden # ".$Pago['Orden'];
        $Info['item_number'] = $Pago['Orden'];
        $xinfo = '?cmd=_xclick';
        foreach($Info as $key => $value) $xinfo.= "&$key=".urlencode(stripslashes($value));
        $K= array('Cliente','CorreoElectronico','NombreCompleto');
        $Cliente= array();
        foreach($K as $key){ if(!empty($Pago[$key])) $Cliente[$key]= $Pago[$key]; }
        $xinfo.= '&custom='.urlencode(json_encode($Cliente));
        $xurl='https://www.'; if($Config['sandbox']) $xurl.='sandbox.';
        $xurl.='paypal.com/cgi-bin/webscr'.$xinfo;
        $rq = $db->query("INSERT INTO grPagoTranx VALUES(".$ID.','. self::stxt($Pago['Orden']).','.$Pago['OrdenTotal'].",'paypalCargo','',". self::stxt($xurl).")");
        header('Location:'.$xurl);
        exit;
    }

    public function cancelado($x=null) {
        $_SESSION['xStore']=0;
    }
    
    public function procesado($x=null) {
        $Info = $_POST;
        $this->set('Info',$Info);
/*
        var_dump($Info); exit;
        Loader::model('grPaypal','gr_pago');
        $Config = grPaypal::Config();
        $db = Loader::db();
        $t = explode (' ', microtime());
        $ID = $t[1].substr($t[0],2,4);
        $jinfo = json_encode($Info);
        $rq = $db->query("INSERT INTO grPagoTranx VALUES(".$ID.",". self::stxt($Info['item_number']).",". self::stxt($Info['mc_gross']).",'paypalProcesado',". self::stxt($Info['payment_status']).",". self::stxt($jinfo).")");
        file_put_contents($Config['ruta'].'/p_'.$Info['item_number'].'_'.$ID,json_encode($Info));
        if($Info['payment_status']=='Completed') grPaypal::Pagado($Info);
        else $rq = $db->query("UPDATE grPago SET pStatus=". self::stxt($Info['payment_status'])." WHERE pOrden=". self::stxt($Info['item_number']));
 */
        Loader::library('grCompras','gr_tienda');
        grCompras::nuevo();
        $_SESSION['xStore']=0;
    }
    
    public function data($x=null) {
        $Info = $_POST;
        $xreq = 'cmd=_notify-validate'; 
        $get_magic_quotes_exists = false; if(function_exists('get_magic_quotes_gpc')) $get_magic_quotes_exists = true;
        foreach ($_POST as $key => $value) {
            if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { $value = urlencode(stripslashes($value)); } else { $value = urlencode($value); }
            $xreq.= "&$key=$value";
        }
        $xurl='https://www.'; if(!empty($Info['test_ipn'])) $xurl.='sandbox.';
        $xurl.='paypal.com/cgi-bin/webscr';
        $ch = curl_init($xurl);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xreq);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        $xres = curl_exec($ch);
        curl_close($ch);
        $db = Loader::db();
        $t = explode (' ', microtime());
        $ID = $t[1].substr($t[0],2,4);
        $jinfo = json_encode($Info);
        $rq = $db->query("INSERT INTO grPagoTranx VALUES(".$ID.",". self::stxt($Info['item_number']).",". self::stxt($Info['mc_gross']).",'paypalData',". self::stxt($Info['payment_status']).",". self::stxt($jinfo).")");
        Loader::model('grPaypal','gr_pago');
        $Config = grPaypal::Config();
        file_put_contents($Config['ruta'].'/pd_'.$Info['item_number'].'_'.$ID,json_encode($Info));
        if($Info['payment_status']=='Completed') grPaypal::Pagado($Info);       
        exit;
    }

    public function xpagadox() {
        $db = Loader::db();
        $R= $db->getRow("SELECT * FROM grPagoTranx WHERE tID=14903036535918");
        $Info= json_decode($R['tInfo'],true);
        echo '<p>'.$Info['item_number'];
        Loader::model('grPaypal','gr_pago');
        grPaypal::Pagado($Info);
        var_dump($Info);
        exit;
    }
    
    function directo($x=null) {
        Loader::library('grRegistro','gr_registro');
        $xrEmail= grRegistro::email();
        if(empty($xrEmail)) $this->redirect('/registro/?msg=pago_registarse');
        $html = Loader::helper('html');
        $this->addHeaderItem($html->javascript('directo.js','gr_pago'));
        $this->set('orden', chr(mt_rand(65,90)) . chr(mt_rand(65,90)) . time() . chr(mt_rand(65,90)));
        $this->set('cliente', grRegistro::id());
    }
    
    function envio($x=null) {
        if(empty($_SERVER['HTTP_REFERER'])) $this->redirect('/');
        $ref=parse_url($_SERVER['HTTP_REFERER']);
        if($_SERVER['HTTP_HOST']!=$ref['host']) $this->redirect('/');
        if(empty($_POST)) $this->redirect('/?e=envio_no_post');
        $xdat=$_POST;
        if(empty($xdat['Cliente'])) $this->redirect('/?e=envio_no_cliente');
        $db = Loader::db();
        $rq= $db->query('UPDATE grRegistro SET rCompras='. self::stxt(json_encode($xdat)) .' WHERE rID='.$xdat['Cliente']);
        $xdat['OrdenTotal']= $xdat['TotalPagar'];
        $oc="\nPago Directo Paypal\nNúmero : ".$xdat['Orden']."\nTOTAL a pagar : $".number_format($xdat['TotalPagar'],2);
        $xdat['OrdenCompra']=$oc;
        $xdat['xtitulos']['OrdenCompra']='Orden de Pago'; 
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
        Loader::model('grForma','gr_forma');
        grForma::Enviar($xdat);
        $t = explode (' ', microtime());
        $ID = $t[1].substr($t[0],2,4);
        $rq= $db->query('INSERT INTO grPago VALUES('.$ID.','.$xdat['Cliente'].','. self::stxt($xdat['CorreoElectronico']).','. self::stxt($xdat['Orden']).','. $xdat['OrdenTotal'].','. self::stxt($xdat['xcmd']).",'',". self::stxt(json_encode($xdat)) .")");
        Loader::model('grPaypal','gr_pago');
        $Config = grPaypal::Config();
        $Info = $Config['info'];
        $Info['amount'] = $xdat['OrdenTotal'];
        $Info['item_name'] = "Pago # ".$xdat['Orden'];
        $Info['item_number'] = $xdat['Orden'];
        $xinfo = '?cmd=_xclick';
        foreach($Info as $key => $value) $xinfo.= "&$key=".urlencode(stripslashes($value));
        $K= array('Cliente','CorreoElectronico','NombreCompleto');
        $Cliente= array();
        foreach($K as $key){ if(!empty($xdat[$key])) $Cliente[$key]= $xdat[$key]; }
        $xinfo.= '&custom='.urlencode(json_encode($Cliente));
        $xurl='https://www.'; if($Config['sandbox']) $xurl.='sandbox.';
        $xurl.='paypal.com/cgi-bin/webscr'.$xinfo;
        $rq = $db->query("INSERT INTO grPagoTranx VALUES(".$ID.','. self::stxt($xdat['Orden']).','.$xdat['OrdenTotal'].",'paypalCargo','',". self::stxt($xurl).")");
        header('Location:'.$xurl);
        exit;
    }

    public function view($op=null) {
        $u= new User();
        if(!$u->isSuperUser()) $this->redirect('/tienda/');
    }

}
?>