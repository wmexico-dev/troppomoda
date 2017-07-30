<?php   

class grRegistro Extends Model {
    
    function inicio() {
        unset($_SESSION['grRegistro']);
        $_SESSION['grRegistro']= array();
        $_SESSION['grRegistro']['t']= time();
        $_SESSION['grRegistro']['email']= null;
        $_SESSION['grRegistro']['tipo']= 0;
        $_SESSION['grRegistro']['id']= 0;
    }
    
    function salir() {
        $_SESSION['grRegistro']['email']= null;
        $_SESSION['grRegistro']['tipo']= 0;
        $_SESSION['grRegistro']['id']= 0;
    }
    
    function email() {
        return $_SESSION['grRegistro']['email'];
    }
    function tipo() {
        return $_SESSION['grRegistro']['tipo'];
    }
    function id() {
        return $_SESSION['grRegistro']['id'];
    }
    
    function Config() {
        $Config= array(
            'asunto'=> 'Tienda TROPPO',
            'remitente'=> 'tienda@troppomoda.com',
            'correo'=> 'tienda@troppomoda.com',
            'redirect'=> null,
            'referer'=> 'troppo'      
        );
        return $Config;
    }
    
    function olvidomail($id=null){
        if(empty($id)) $this->redirect('/?e=registro_olvidomail_noid');
        $db = Loader::db();
        $rq= $db->query('SELECT * FROM grRegistro WHERE rID='.$id);
        if($R=$rq->fetchrow()){
            $Cnf = grRegistro::Config();
            $codigo= $R['rPwdData'] . $R['rPwdTime'];
            $codigo= md5($codigo);
            $chk= $codigo . $id;
            $chk= md5($chk);
            $email= $R['rEmail'];
            $subject = mb_encode_mimeheader($Cnf['asunto'] .' - Olvido Contraseña','UTF-8');
            $headers = 'From: '.$Cnf['remitente']. PHP_EOL.
                'Reply-To: '.$Cnf['remitente']. PHP_EOL.
                'MIME-Version: 1.0'. PHP_EOL.
                'Content-Type: text/plain; charset=UTF-8; format=flowed'. PHP_EOL.
                'Content-Transfer-Encoding: 8bit'. PHP_EOL;
            $message = 'Para asignar la nueva contraseña de la Tienda TROPPO'. PHP_EOL.
                'debe confirmar el cambio en el siguiente enlace:'. PHP_EOL.
                '---'. PHP_EOL.
                'https://troppomoda.com/registro/olvidoConfirmar/'.$id.'/'.$codigo.'/'.$chk. PHP_EOL.
                '---'. PHP_EOL.
                PHP_EOL.
                'Si no solicitó cambio de contraseña, ignore el mensaje.'. PHP_EOL;
            mail($R['rEmail'], $subject, $message, $headers);
        } else $this->redirect('/?e=registro_olvidomail_malid');
    }
    
}
?>