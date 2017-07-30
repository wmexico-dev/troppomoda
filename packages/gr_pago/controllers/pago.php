<?php   defined("C5_EXECUTE") or die(_("Access Denied."));

Class PagoController Extends Controller {
    
    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }
    
    public function view($op=null) {
        Loader::library('grRegistro','gr_registro');
        $xrEmail= grRegistro::email();
        if(!empty($xrEmail)) $this->redirect('/pago/paypal/directo/');
        $this->redirect('/registro/');
    }
    
}