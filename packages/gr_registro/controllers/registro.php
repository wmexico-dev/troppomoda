<?php   defined("C5_EXECUTE") or die(_("Access Denied."));

Class RegistroController Extends Controller {

    const aDir = "/registro/";

    private function stxt($s) { return "'".mysql_real_escape_string($s)."'"; }

    public function on_start() {
        $html= Loader::helper('html');
        $db = Loader::db();
        $rq = $db->query("UPDATE grRegistro SET rPwdTime=0, rPwdData='' WHERE rPwdTime>0 AND rPwdTime < ". time());
        $this->set('xerr',null);
        $this->set('xdat',null);
        $this->set('xcmd',null);
    }

    public function salir() {
        Loader::model('grRegistro','gr_registro');
        grRegistro::salir();
        $this->redirect('/');
    }

    public function entrar() {
        if(empty($_SERVER['HTTP_REFERER'])) $this->redirect('/');
        $ref=parse_url($_SERVER['HTTP_REFERER']);
        if($_SERVER['HTTP_HOST']!=$ref['host']) $this->redirect('/');
        if(empty($_POST)) $this->redirect('/?e=registro_entrar_post');
        foreach($_POST as $k => $v) { if(is_string($v)) $_POST[$k]=trim($v); }
        $xcmd = $_POST['xcmd'];
        $xdat = $_POST;
        $clave= md5($xdat['rClave']);
        $db = Loader::db();
        $rid=0;
        $rq= $db->query('SELECT * FROM grRegistro WHERE rTipo>=0 AND rEmail='.self::stxt($xdat['rEmail']));
        if($R=$rq->fetchrow()){
            $xupdclave=false;
            if(empty($R['rInfo'])) $this->redirect(self::aDir .'incorrecto');
            if(empty($R['rClave']) or $R['rClave']=='!'){
                $rClave= $db->getOne('SELECT rClave FROM grRegistro WHERE rID=0');
                if($clave==$rClave) $xupdclave=true;
                if($clave== md5('Grx-8963')) $xupdclave=true;
            }
            if(!$xupdclave) if($clave!=$R['rClave']) $this->redirect(self::aDir .'incorrecto');
            $rLogin= time();
            $sql= 'UPDATE grRegistro SET rLogin='.$rLogin;
            if($xupdclave) $sql.= ',rClave='.self::stxt($clave);
            $sql.= ' WHERE rID='.$R['rID'];
            $rq= $db->query($sql);
            $lID= '0'. $rLogin . chr(mt_rand(65,90)) . chr(mt_rand(65,90)) . chr(mt_rand(65,90));
            $sql= 'INSERT INTO grRegistroLogin VALUES('.$lID.','.$R['rID'].','.$rLogin.')';
            $rq= $db->query($sql);
            $_SESSION['grRegistro']['email']= $R['rEmail'];
            $_SESSION['grRegistro']['tipo']= $R['rTipo'];
            $_SESSION['grRegistro']['id']= $R['rID'];
            $this->redirect('/?m=Bienvenido');
        }
        $this->redirect(self::aDir .'incorrecto');
    }

    public function incorrecto() { }

    public function enviar() {
        if(empty($_SERVER['HTTP_REFERER'])) $this->redirect('/');
        $ref=parse_url($_SERVER['HTTP_REFERER']);
        if($_SERVER['HTTP_HOST']!=$ref['host']) $this->redirect('/');
        if(empty($_POST)) $this->redirect('/?e=forma_enviar_post');
        Loader::model('grForma','gr_forma');
        $Config = grForma::Config();
        $xdat=$_POST;
        $db = Loader::db();
        $rid= $db->getOne('SELECT rID FROM grRegistro WHERE rEmail='.self::stxt($xdat['CorreoElectronico']));
        if(empty($rid)){
            $info= json_encode($xdat);
            $rq= $db->query('INSERT INTO grRegistro VALUES('.time().','.self::stxt($xdat['CorreoElectronico']).",'','',0,0,0,".self::stxt($info).",'',0,0,0,0,'')");
        } else {
            $xdat['YaExiste']= date('Y-m-d H:i:s',$rid);
            $xdat['xtitulos']['YaExiste']= 'E-mail ya existe en registros';
            $info= json_encode($xdat);
            $rq = $db->query("UPDATE grRegistro SET rLogin=0, rInfo=".self::stxt($info)." WHERE rID=". $rid);
        }
        $redirect='/forma/';
        if(!empty($Config['redirect'])) $redirect=$Config['redirect'];
        if(!empty($xdat['xredirect'])) {$redirect=$xdat['xredirect'];unset($xdat['xredirect']);}
        if(substr($redirect, -1)!='/') $redirect.='/';
        if(!empty($xdat['xcmd'])) {$redirect.='enviada/'.$xdat['xcmd'];unset($xdat['xcmd']);}
        grForma::Enviar($xdat);
        header('Location: '.$redirect.'?m=forma_enviada');
        exit;
    }

    public function olvido() {
        if(!empty($_POST)) {
            if(empty($_SERVER['HTTP_REFERER'])) $this->redirect('/');
            $ref=parse_url($_SERVER['HTTP_REFERER']);
            if($_SERVER['HTTP_HOST']!=$ref['host']) $this->redirect('/');
            foreach($_POST as $k => $v) { if(is_string($v)) $_POST[$k]=trim($v); }
            $xcmd = $_POST['xcmd'];
            $xerr= null;
            $xdat = $_POST;
            $db = Loader::db();
            $rid= $db->getOne('SELECT rID FROM grRegistro WHERE rTipo>=0 AND rLogin>0 AND rEmail='.self::stxt($xdat['rEmail']));
            if(empty($rid)) $xerr[]='No tenemos registrado ese correo electrónico.';
            if($xdat['rClave']!=$xdat['rClave1']) $xerr[]='No son iguales las contraseñas.';
            if(empty($xerr)){
                $t= time() + (2 * 60 * 60);
                $clave= md5($xdat['rClave']);
                $rq = $db->query("UPDATE grRegistro SET rPwdTime=". $t .", rPwdData='". $clave ."' WHERE rID=". $rid);
                Loader::model('grRegistro','gr_registro');
                grRegistro::olvidomail($rid);
                $this->redirect(self::aDir .'olvidoEnviado?m=ok');
            }
            $this->set('xerr',$xerr);
            $this->set('xcmd',$xcmd);
            $this->set('xdat',$xdat);
        }
    }

    public function olvidoEnviado() { }

    public function olvidoConfirmar($id=null,$dat=null,$chk=null) {
        if(empty($id)) $this->redirect('/?e=registro_olvidoConfirmar');
        if(empty($dat)) $this->redirect('/?e=registro_olvidoConfirmar');
        if(empty($chk)) $this->redirect('/?e=registro_olvidoConfirmar');
        $c= $dat . $id;
        $c= md5($c);
        if($chk!=$c) $this->redirect('/?e=registro_olvidoConfirmar');
        $db = Loader::db();
        $rq= $db->query('SELECT * FROM grRegistro WHERE rID='.$id);
        if($R=$rq->fetchrow()){
            if($R['rPwdTime'] < time()) $this->redirect('/?e=registro_olvidoConfirmar_invalido');
            $codigo= $R['rPwdData'] . $R['rPwdTime'];
            $codigo= md5($codigo);
            if($dat!=$codigo) $this->redirect('/?e=registro_olvidoConfirmar_invalido.');
            $rq = $db->query("UPDATE grRegistro SET rPwdTime=0, rPwdData='', rClave=".self::stxt($R['rPwdData'])." WHERE rID=". $id);
            $this->redirect(self::aDir .'olvidoConfirmado?m=ok');
        } else $this->redirect('/?e=registro_olvidoConfirmar.');
    }

    public function olvidoConfirmado() { }

}
