<?php defined("C5_EXECUTE") or die(_("Access Denied."));

Class FormaController Extends Controller {

    const aDir = "/";
    
    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }
    
    public function on_start() {
        $this->set('xerr',null);
    }

    function view($xcmd=null) {
        $u= new User();
        if(empty($_SERVER['HTTP_REFERER']) && !$u->isSuperUser()) $this->redirect('/');
        $this->set('xcmd',$xcmd);
    }
    
    public function enviar() {
        if(empty($_SERVER['HTTP_REFERER'])) $this->redirect('/');
        $ref=parse_url($_SERVER['HTTP_REFERER']);
        if($_SERVER['HTTP_HOST']!=$ref['host']) $this->redirect('/');
        if(empty($_POST)) $this->redirect('/?e=forma_enviar_post');
        $xdat=$_POST;
        Loader::model('grForma','gr_forma');
        $Config = grForma::Config();
        if(!empty($Config['referer'])) {
            $pos = strpos($_SERVER['HTTP_REFERER'], $Config['referer']);
            if ($pos === false) $this->redirect('/?e=forma_enviar_ref');
        }
        $redirect='/forma/';
        if(!empty($Config['redirect'])) $redirect=$Config['redirect'];
        if(!empty($xdat['xredirect'])) {$redirect=$xdat['xredirect'];unset($xdat['xredirect']);}
        if(substr($redirect, -1)!='/') $redirect.='/';
        if(!empty($xdat['xcmd'])) {$redirect.='enviada/'.$xdat['xcmd'];unset($xdat['xcmd']);}
        grForma::Enviar($xdat);
        header('Location: '.$redirect.'?msg=forma_enviada');
        exit;
    }

    function enviada($xinfo=null) {
        $this->set('xinfo',$xinfo);
    }
    
}
?>